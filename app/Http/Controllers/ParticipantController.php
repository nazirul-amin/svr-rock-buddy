<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Models\Participant;
use Exception;
use Inertia\Inertia;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->handleResourceAction(
            function () {
                $participants = Participant::paginate(10);
                return Inertia::render('participants/Index', [
                    'participants' => $participants,
                ]);
            },
            'index',
            'Participant',
            '',
            'Unable to load participants. Please try again.'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->handleResourceAction(
            function () {
                return Inertia::render('participants/Create');
            },
            'create',
            'Participant',
            '',
            'Unable to display the form. Please try again.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParticipantRequest $request)
    {
        return $this->handleResourceAction(
            function () use ($request) {
                $validated = $request->validated();
                $participant = Participant::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
                return redirect()->route('participants.index')
                    ->with('success', 'Participant created successfully');
            },
            'store',
            'Participant',
            "Participant created: {$participant->id}",
            'Failed to create participant. Please try again.',
            [],
            true
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        return $this->handleResourceAction(
            function () use ($participant) {
                return Inertia::render('participants/Show', [
                    'participant' => $participant,
                ]);
            },
            'show',
            'Participant',
            '',
            'Unable to display participant. Please try again.'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        return $this->handleResourceAction(
            function () use ($participant) {
                return Inertia::render('participants/Edit', [
                    'participant' => $participant,
                ]);
            },
            'edit',
            'Participant',
            '',
            'Unable to display edit form. Please try again.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        return $this->handleResourceAction(
            function () use ($request, $participant) {
                $validated = $request->validated();
                $participant->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
                return redirect()->route('participants.index')
                    ->with('success', 'Participant updated successfully');
            },
            'update',
            'Participant',
            "Participant updated: {$participant->id}",
            'Failed to update participant. Please try again.',
            [],
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        return $this->handleResourceAction(
            function () use ($participant) {
                $participantId = $participant->id;
                $participant->delete();
                return redirect()->route('participants.index')
                    ->with('success', 'Participant deleted successfully');
            },
            'destroy',
            'Participant',
            "Participant deleted: {$participant->id}",
            'Failed to delete participant. Please try again.',
            [],
            true
        );
    }
}
