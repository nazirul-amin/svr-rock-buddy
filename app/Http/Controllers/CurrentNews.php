<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\AiSuggestImprovement;

class CurrentNews extends Controller
{
    use AiSuggestImprovement;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $activeResult = Result::where('is_active', true)->with('themes')->first();
        $themeIds = $activeResult ? $activeResult->themes->pluck('id')->toArray() : [];

        $submissions = Submission::with(['participant', 'theme'])
            ->whereIn('theme_id', $themeIds)
            ->get()->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'image' => $submission->path ? asset('storage/'.$submission->path) : null,
                    'participant' => $submission->participant ? ['id' => $submission->participant->id, 'name' => $submission->participant->name] : null,
                    'theme' => $submission->theme ? ['id' => $submission->theme->id, 'name' => $submission->theme->name] : null,
                    'score' => $submission->score ?? 0,
                    'rank' => null,
                    'total_score' => 0,
                    'theme_scores' => [],
                ];
            });

        $themesData = [];
        foreach ($activeResult ? $activeResult->themes : [] as $theme) {
            $themesData[$theme->id] = [
                'theme' => [
                    'id' => $theme->id,
                    'name' => $theme->name,
                ],
                'participants' => [],
                'top3' => [],
            ];
        }
        foreach ($submissions as $submission) {
            $themeId = $submission['theme']['id'] ?? null;
            $participantId = $submission['participant']['id'] ?? null;
            if (! $themeId || ! $participantId) {
                continue;
            }
            if (! isset($themesData[$themeId]['participants'][$participantId])) {
                $themesData[$themeId]['participants'][$participantId] = [
                    'participant' => $submission['participant'],
                    'score' => 0,
                    'submission' => $submission,
                ];
            }
            $themesData[$themeId]['participants'][$participantId]['score'] += $submission['score'] ?? 0;
        }

        $participantTotals = [];
        foreach ($submissions as $submission) {
            $participantId = $submission['participant']['id'] ?? null;
            if (! $participantId) {
                continue;
            }
            if (! isset($participantTotals[$participantId])) {
                $participantTotals[$participantId] = [
                    'participant' => $submission['participant'],
                    'score' => 0,
                    'submissions' => [],
                ];
            }
            $participantTotals[$participantId]['score'] += $submission['score'] ?? 0;
            $participantTotals[$participantId]['submissions'][] = $submission;
        }
        uasort($participantTotals, fn ($a, $b) => $b['score'] <=> $a['score']);
        $top3 = array_slice(array_values($participantTotals), 0, 3);

        $highlightedResult = null;
        if ($request->has('participant') && $request->has('result')) {
            $highlightParticipantId = $request->query('participant');
            $highlightResultId = $request->query('result');
            $highlightResult = Result::with('themes')->find($highlightResultId);
            $highlightThemeIds = $highlightResult ? $highlightResult->themes->pluck('id')->toArray() : [];
            $highlightSubmissions = Submission::with(['theme'])
                ->where('participant_id', $highlightParticipantId)
                ->whereIn('theme_id', $highlightThemeIds)
                ->get();
            $overallRank = null;
            foreach (array_values($participantTotals) as $idx => $row) {
                if ($row['participant']['id'] == $highlightParticipantId) {
                    $overallRank = $idx + 1;
                    break;
                }
            }
            $highlightedResult = [
                'participant_id' => $highlightParticipantId,
                'submissions' => $highlightSubmissions->map(function ($submission) {
                    return [
                        'id' => $submission->id,
                        'image' => $submission->path ? asset('storage/'.$submission->path) : null,
                        'theme' => $submission->theme ? ['id' => $submission->theme->id, 'name' => $submission->theme->name] : null,
                        'score' => $submission->score ?? 0,
                    ];
                }),
                'overall_rank' => $overallRank,
                'total_score' => $participantTotals[$highlightParticipantId]['score'] ?? 0,
            ];

            if ($highlightedResult['overall_rank'] !== 1) {
                $highlightedResult['suggestion'] = $this->suggestImprovement($highlightSubmissions);
            }
        }

        return Inertia::render('News', [
            'top3' => $top3,
            'title' => $activeResult->themes->pluck('name')->when(count($activeResult->themes) > 1, fn ($names) => $names->implode(' x ')),
            'submissions' => $submissions,
            'highlightedResult' => $highlightedResult,
        ]);
    }
}
