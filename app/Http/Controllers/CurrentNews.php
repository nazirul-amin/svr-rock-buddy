<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Submission;
use App\Traits\AiSuggestImprovement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Collection;

class CurrentNews extends Controller
{
    use AiSuggestImprovement;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $activeResult = $this->getActiveResult();
        if (! $activeResult) {
            return Inertia::render('News', [
                'top3'             => collect(),
                'title'            => null,
                'submissions'      => collect(),
                'highlightedResult'=> null,
            ]);
        }

        $themeIds    = $activeResult->themes->pluck('id');
        $submissions = $this->getSubmissions($themeIds);

        $themesData        = $this->assembleThemeData($submissions, $activeResult->themes);
        $participantTotals = $this->calculateParticipantTotals($themesData);
        $top3              = $this->getTopParticipants($participantTotals, $submissions);

        $highlightedResult = $this->buildHighlightedResult(
            $request->query('participant'),
            $request->query('result'),
            $participantTotals
        );

        return Inertia::render('News', [
            'top3'             => $top3,
            'title'            => $this->formatTitle($activeResult->themes),
            'submissions'      => $submissions,
            'highlightedResult'=> $highlightedResult,
        ]);
    }

    /**
     * Fetch the active result with its themes.
     */
    private function getActiveResult(): ?Result
    {
        return Result::with('themes')
            ->where('is_active', true)
            ->first();
    }

    /**
     * Retrieve and map submissions for the given theme IDs.
     */
    private function getSubmissions(Collection|array $themeIds): Collection
    {
        return Submission::with(['participant', 'theme'])
            ->whereIn('theme_id', $themeIds)
            ->get()
            ->map(fn (Submission $submission) => [
                'id'          => $submission->id,
                'image'       => $submission->path ? asset("storage/{$submission->path}") : null,
                'participant' => $submission->participant?->only('id', 'name'),
                'theme'       => $submission->theme?->only('id', 'name'),
                'score'       => $submission->score ?? 0,
            ]);
    }

    /**
     * Aggregate submissions by theme and participant.
     */
    private function assembleThemeData(Collection $submissions, Collection $themes): array
    {
        $data = [];
        foreach ($themes as $theme) {
            $data[$theme->id] = [
                'theme'        => $theme->only('id', 'name'),
                'participants' => [],
            ];
        }

        foreach ($submissions as $sub) {
            $tId = $sub['theme']['id'] ?? null;
            $pId = $sub['participant']['id'] ?? null;
            if (! $tId || ! $pId) {
                continue;
            }

            $entry =& $data[$tId]['participants'][$pId];
            if (! isset($entry)) {
                $entry = [
                    'participant'   => $sub['participant'],
                    'total_score'   => 0,
                    'attempt_count' => 0,
                ];
            }

            $entry['total_score']   += $sub['score'];
            $entry['attempt_count']++;
        }

        foreach ($data as &$themeEntry) {
            foreach ($themeEntry['participants'] as &$pData) {
                $pData['average_score'] = $pData['attempt_count']
                    ? $pData['total_score'] / $pData['attempt_count']
                    : 0;
            }
            unset($pData);
        }
        unset($themeEntry);

        return $data;
    }

    /**
     * Compute overall participant average score across all themes.
     */
    private function calculateParticipantTotals(array $themesData): Collection
    {
        $totals = [];
        foreach ($themesData as $themeId => $themeEntry) {
            foreach ($themeEntry['participants'] as $pId => $pData) {
                $totals[$pId]['participant'] = $pData['participant'];
                $totals[$pId]['scores'][]   = $pData['average_score'];
            }
        }

        return collect($totals)
            ->map(fn ($data) => [
                'participant'  => $data['participant'],
                'score'        => array_sum($data['scores']),
                'theme_scores' => $data['scores'],
            ])
            ->sortByDesc('score')
            ->values();
    }

    /**
     * Get top participants and their submissions.
     */
    private function getTopParticipants(Collection $participantTotals, Collection $submissions, int $limit = 3): array
    {
        return $participantTotals
            ->take($limit)
            ->map(fn ($entry) => array_merge($entry, [
                'submissions' => $submissions
                    ->filter(fn($s) => $s['participant']['id'] === $entry['participant']['id'])
                    ->values()
                    ->all(),
            ]))
            ->all();
    }

    /**
     * Build a highlighted result for a specific participant and result.
     */
    private function buildHighlightedResult(?string $participantId, ?string $resultId, Collection $participantTotals): ?array
    {
        if (! $participantId || ! $resultId) {
            return null;
        }

        $result = Result::with('themes')->find($resultId);
        if (! $result) {
            return null;
        }

        $themeIds = $result->themes->pluck('id');
        $subs = Submission::with('theme')
            ->where('participant_id', $participantId)
            ->whereIn('theme_id', $themeIds)
            ->get();

        $overallRank = $participantTotals->search(fn ($entry) => $entry['participant']['id'] === $participantId) + 1;

        $highlight = [
            'participant_id' => $participantId,
            'submissions'    => $subs->map(fn ($sub) => [
                'id'    => $sub->id,
                'image' => $sub->path ? asset("storage/{$sub->path}") : null,
                'theme' => $sub->theme?->only('id', 'name'),
                'score' => $sub->score,
            ]),
            'overall_rank'   => $overallRank,
            'total_score'    => $participantTotals->firstWhere('participant.id', $participantId)['score'] ?? 0,
        ];

        if ($overallRank !== 1) {
            $highlight['suggestion'] = $this->suggestImprovement($subs);
        }

        return $highlight;
    }

    /**
     * Generate a title string from theme names.
     */
    private function formatTitle(Collection $themes): ?string
    {
        if ($themes->isEmpty()) {
            return null;
        }

        $names = $themes->pluck('name');
        return $names->count() > 1
            ? $names->implode(' x ')
            : $names->first();
    }
}
