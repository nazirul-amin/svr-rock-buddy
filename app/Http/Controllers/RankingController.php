<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Participant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function theme(Theme $theme)
    {
        $participants = Participant::select('participants.*')
            ->join('submissions', 'participants.id', '=', 'submissions.participant_id')
            ->where('submissions.theme_id', $theme->id)
            ->selectRaw('SUM(submissions.score) as total_score')
            ->groupBy('participants.id')
            ->orderByDesc('total_score')
            ->get();

        return Inertia::render('rankings/Theme', [
            'theme' => $theme,
            'participants' => $participants,
        ]);
    }

    public function overall()
    {
        $participants = Participant::orderBy('score', 'desc')->get();

        return Inertia::render('rankings/Overall', [
            'participants' => $participants,
        ]);
    }
}
