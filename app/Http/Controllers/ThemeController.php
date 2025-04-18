<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThemeRequest;
use App\Http\Requests\UpdateThemeRequest;
use App\Models\Theme;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->handleResourceAction(
            function () {
                $themes = Theme::paginate(10);

                return Inertia::render('themes/Index', [
                    'themes' => $themes,
                ]);
            },
            'index',
            'Theme',
            '',
            'Unable to load themes. Please try again.'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->handleResourceAction(
            function () {
                return Inertia::render('themes/Create');
            },
            'create',
            'Theme',
            '',
            'Unable to display the form. Please try again.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeRequest $request)
    {
        return $this->handleResourceAction(
            function () use ($request) {
                $validated = $request->validated();
                if ($request->hasFile('poster')) {
                    $validated['poster'] = $request->file('poster')->store('posters', 'public');
                }
                Theme::create($validated);

                return redirect()->route('themes.index')
                    ->with('success', 'Created');
            },
            'store',
            'Theme',
            'Created',
            'Failed to create theme. Please try again.',
            [],
            true
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme)
    {
        return $this->handleResourceAction(
            function () use ($theme) {
                $submissions = $theme->submissions()
                    ->with('participant')
                    ->orderByDesc('score')
                    ->get();

                return Inertia::render('themes/Show', [
                    'theme' => $theme,
                    'submissions' => $submissions,
                ]);
            },
            'show',
            'Theme',
            '',
            'Unable to display theme. Please try again.'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme)
    {
        return $this->handleResourceAction(
            function () use ($theme) {
                return Inertia::render('themes/Edit', [
                    'theme' => $theme,
                ]);
            },
            'edit',
            'Theme',
            '',
            'Unable to display edit form. Please try again.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeRequest $request, Theme $theme)
    {
        return $this->handleResourceAction(
            function () use ($request, $theme) {
                $validated = $request->validated();
                // Handle poster removal
                if ($request->input('_remove_poster')) {
                    if ($theme->poster && Storage::disk('public')->exists($theme->poster)) {
                        Storage::disk('public')->delete($theme->poster);
                    }
                    $validated['poster'] = null;
                }
                // Handle poster upload
                elseif ($request->hasFile('poster')) {
                    // Delete old poster if exists
                    if ($theme->poster && Storage::disk('public')->exists($theme->poster)) {
                        Storage::disk('public')->delete($theme->poster);
                    }
                    $validated['poster'] = $request->file('poster')->store('posters', 'public');
                } else {
                    // Do not update the poster field if not changed
                    unset($validated['poster']);
                }
                $theme->update($validated);

                return redirect()->route('themes.index')
                    ->with('success', 'Theme updated successfully');
            },
            'update',
            'Theme',
            'Theme updated successfully',
            'Failed to update theme. Please try again.',
            [],
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        return $this->handleResourceAction(
            function () use ($theme) {
                // Delete poster file if it exists
                if ($theme->poster && Storage::disk('public')->exists($theme->poster)) {
                    Storage::disk('public')->delete($theme->poster);
                }
                $theme->delete();

                return redirect()->route('themes.index')
                    ->with('success', 'Theme deleted successfully');
            },
            'destroy',
            'Theme',
            'Theme deleted successfully',
            'Failed to delete theme. Please try again.',
            [],
            true
        );
    }
}
