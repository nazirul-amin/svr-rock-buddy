<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThemeRequest;
use App\Http\Requests\UpdateThemeRequest;
use App\Models\Theme;
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
                return Inertia::render('themes/Show', [
                    'theme' => $theme,
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
