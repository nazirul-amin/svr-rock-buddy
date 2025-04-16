<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Models\Participant;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;
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
                Participant::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);

                return redirect()->route('participants.index')
                    ->with('success', 'Participant created successfully');
            },
            'store',
            'Participant',
            'Participant created successfully',
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
            'Participant updated successfully',
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
                $participant->delete();

                return redirect()->route('participants.index')
                    ->with('success', 'Participant deleted successfully');
            },
            'destroy',
            'Participant',
            'Participant deleted successfully',
            'Failed to delete participant. Please try again.',
            [],
            true
        );
    }

    /**
     * Import participants from a CSV or Excel file.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
        ]);
        try {
            Excel::import(new ParticipantsImport, $request->file('file'));
            return redirect()->route('participants.index')->with('success', 'Participants imported successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
