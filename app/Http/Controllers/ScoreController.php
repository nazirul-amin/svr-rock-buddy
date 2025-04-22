<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\Score;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScoreRequest $request, Submission $submission)
    {
        return $this->handleResourceAction(
            function () use ($request, $submission) {
                $validated = $request->validated();
                $user = Auth::user();

                $score = $submission->scores()->firstOrNew(['user_id' => $user->id]);
                $score->score = $validated['score'];
                $score->user_id = $user->id;
                $score->submission_id = $submission->id;
                $score->save();

                $totalScore = $submission->scores()->avg('score');
                $submission->score = round($totalScore, 2);
                $submission->save();

                $participant = $submission->participant;
                $participant->score = $participant->submissions()->sum('score');
                $participant->save();

                return redirect()->route('submissions.show', $submission)
                    ->with('success', 'Score submitted successfully');
            },
            action: 'store',
            resource: 'Score',
            useTransaction: true,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Score $score)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Score $score)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScoreRequest $request, Score $score)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Score $score)
    {
        //
    }
}
