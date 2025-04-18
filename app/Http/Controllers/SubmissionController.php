<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;

use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Models\Participant;
use App\Models\Submission;

class SubmissionController extends Controller
{

    /**
     * Show the public submission form for a theme.
     */
    public function publicForm(Theme $theme)
    {
        return inertia('submissions/PublicForm', [
            'theme' => $theme,
        ]);
    }

    /**
     * Handle public submission.
     */
    public function publicStore(StoreSubmissionRequest $request, Theme $theme)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('file')) {
            $validated['path'] = $request->file('file')->store('submissions', 'public');
        }

        // Find participant by email (must exist due to validation)
        $participant = Participant::where('email', $validated['email'])->first();
        if (!$participant) {
            // This should not occur if validation is correct, but is a safety net
            return back()->withErrors(['email' => 'Email not found. Please use a registered email.'])->withInput();
        }
        $validated['theme_id'] = $theme->id;
        $validated['participant_id'] = $participant->id;
        unset($validated['email']);

        // Create the submission
        Submission::create($validated);
        return redirect()->route('submissions.public.thankyou', $theme)->with('success', 'Submission received!');
    }
    /**
     * Thank you page after public submission.
     */
    public function publicThankYou(Theme $theme)
    {
        return inertia('submissions/ThankYou', ['theme' => $theme]);
    }

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
    public function store(StoreSubmissionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        $submission->load(['participant', 'theme', 'scores.user']);
        return inertia('submissions/Show', [
            'submission' => $submission,
            'scores' => $submission->scores->map(function($score) {
                return [
                    'id' => $score->id,
                    'user' => [
                        'id' => $score->user->id,
                        'name' => $score->user->name,
                        'email' => $score->user->email,
                    ],
                    'score' => $score->score,
                ];
            }),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmissionRequest $request, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $submission)
    {
        //
    }
}
