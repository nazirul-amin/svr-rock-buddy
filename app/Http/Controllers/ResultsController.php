<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Submission;
use App\Models\Theme;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Models\Participant;

class ResultsController extends Controller
{
    public function check(Result $result)
    {
        return Inertia::render('results/Check', [
            'theme' => $result->themes->pluck('name')->when(count($result->themes) > 1, fn($names) => $names->implode(' x ')),
            'result' => $result,
        ]);
    }

    public function checkSubmit(Request $request, Result $result)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $participant = Participant::where('email', $request->email)->first();
        if (!$participant) {
            return back()->withErrors(['email' => 'No participant found for this email.'])->withInput();
        }
        $themeIds = $result->themes->pluck('id');
        $submissions = Submission::where('participant_id', $participant->id)
            ->whereIn('theme_id', $themeIds)
            ->pluck('id')
            ->toArray();
        if (empty($submissions)) {
            return back()->withErrors(['email' => 'No submissions found for this participant in this result.'])->withInput();
        }
        return redirect()->route('news', [
            'participant' => $participant->id,
            'result' => $result->id,
        ]);
    }

    public function create()
    {
        return $this->handleResourceAction(
            function () {
                $themes = Theme::all();
                return Inertia::render('results/Create', [
                    'themes' => $themes,
                ]);
            },
            'create',
            'Result',
            '',
            'Unable to display the form. Please try again.'
        );
    }

    public function show(Result $result)
    {
        return $this->handleResourceAction(
            function () use ($result) {
                $result->load('themes');
                return Inertia::render('results/Show', [
                    'result' => $result,
                ]);
            },
            'show',
            'Result',
            '',
            'Unable to display result. Please try again.'
        );
    }

    public function edit(Result $result)
    {
        return $this->handleResourceAction(
            function () use ($result) {
                $result->load('themes');
                $themes = Theme::all();
                return Inertia::render('results/Edit', [
                    'result' => $result,
                    'themes' => $themes,
                ]);
            },
            'edit',
            'Result',
            '',
            'Unable to display edit form. Please try again.'
        );
    }

    public function index()
    {
        return $this->handleResourceAction(
            function () {
                $results = Result::with('themes')->paginate(10);
                $themes = Theme::all();
                return Inertia::render('results/Index', [
                    'results' => $results,
                    'themes' => $themes,
                ]);
            },
            'index',
            'Result',
            '',
            'Unable to load results. Please try again.'
        );
    }

    public function store(StoreResultRequest $request)
    {
        return $this->handleResourceAction(
            function () use ($request) {
                $validated = $request->validated();
                $result = Result::create([
                    'name' => $validated['name'],
                    'is_active' => $validated['is_active'],
                ]);
                if (!empty($validated['theme_ids'])) {
                    $result->themes()->sync($validated['theme_ids']);
                }
                return redirect()->route('results.index')->with('success', 'Result created successfully');
            },
            'store',
            'Result',
            'Result created successfully',
            'Failed to create result. Please try again.',
            [],
            true
        );
    }

    public function update(UpdateResultRequest $request, Result $result)
    {
        return $this->handleResourceAction(
            function () use ($request, $result) {
                $data = $request->validated();
                $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;
                $result->update($data);
                if (!empty($data['theme_ids'])) {
                    $result->themes()->sync($data['theme_ids']);
                }
                return redirect()->route('results.index')->with('success', 'Result updated successfully');
            },
            'update',
            'Result',
            'Result updated successfully',
            'Failed to update result. Please try again.',
            [],
            true
        );
    }

    public function destroy(Result $result)
    {
        return $this->handleResourceAction(
            function () use ($result) {
                $result->delete();
                return redirect()->back()->with('success', 'Results deleted.');
            },
            'destroy',
            'Result',
            'Result deleted successfully',
            'Failed to delete result. Please try again.',
            [],
            true
        );
    }
}
