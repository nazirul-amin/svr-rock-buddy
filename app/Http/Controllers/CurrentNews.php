<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Theme;
use Illuminate\Http\Request;

class CurrentNews extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $submissions = Submission::with(['participant', 'theme'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'image' => $s->path ? asset('storage/' . $s->path) : null,
                    'participant' => $s->participant ? ['name' => $s->participant->name] : null,
                    'theme' => $s->theme ? ['title' => $s->theme->title] : null,
                    'score' => $s->score ?? 0,
                ];
            });

        $theme = Theme::where('to', '<', now())
            ->orderByDesc('to')
            ->first();
        $top3 = [];

        if ($theme) {
            $top3 = Submission::with('participant')
                ->where('theme_id', $theme->id)
                ->orderByDesc('score')
                ->take(3)
                ->get()
                ->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'image' => $s->path ? asset('storage/' . $s->path) : null,
                        'participant' => $s->participant ? ['name' => $s->participant->name] : null,
                        'score' => $s->score ?? 0,
                    ];
                });
        }
        return inertia('News', [
            'submissions' => $submissions,
            'theme' => $theme,
            'top3' => $top3,
        ]);
    }
}
