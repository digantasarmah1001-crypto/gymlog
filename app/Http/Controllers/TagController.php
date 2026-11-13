<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Tags: a shared list of labels for workouts (many-to-many). Only admins manage the list.
 * All tag routes require login (routes/web.php).
 */
class TagController extends Controller
{
    public function index(): View
    {
        return view('tags.index', [
            'tags' => Tag::withCount('workouts')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Tag::class);

        return view('tags.create', ['tag' => new Tag]);
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        $tag = Tag::create($request->validated());

        return redirect()->route('tags.index')->with('status', "Tag \"{$tag->name}\" added.");
    }

    /**
     * A tag with the workouts that use it. Workouts are private:
     * you only see your own (an admin sees all).
     */
    public function show(Request $request, Tag $tag): View
    {
        $user = $request->user();

        $workouts = $tag->workouts()
            ->visibleTo($user)
            ->with('user')
            ->latest('date')
            ->get();

        return view('tags.show', ['tag' => $tag, 'workouts' => $workouts]);
    }

    public function edit(Tag $tag): View
    {
        Gate::authorize('update', $tag);

        return view('tags.edit', ['tag' => $tag]);
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return redirect()->route('tags.index')->with('status', 'Tag renamed.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        Gate::authorize('delete', $tag);

        $tag->delete(); // the pivot rows go too (cascadeOnDelete), the workouts stay

        return redirect()->route('tags.index')->with('status', "Tag \"{$tag->name}\" deleted.");
    }
}
