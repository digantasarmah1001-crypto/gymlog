@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <div class="page-header">
        <h1>Tags</h1>
        @can('create', App\Models\Tag::class)
            <a href="{{ route('tags.create') }}" class="btn btn-primary">+ New tag</a>
        @endcan
    </div>

    <div class="card">
        <table>
            <thead><tr><th>Tag</th><th>Used on (all athletes)</th><th></th></tr></thead>
            <tbody>
                @forelse ($tags as $tag)
                    <tr>
                        <td><a href="{{ route('tags.show', $tag) }}" class="tag">{{ $tag->name }}</a></td>
                        <td class="num">{{ $tag->workouts_count }} workouts</td>
                        <td class="actions">
                            @can('update', $tag)
                                <a href="{{ route('tags.edit', $tag) }}" class="btn btn-sm">Rename</a>
                            @endcan
                            @can('delete', $tag)
                                <x-delete-button :action="route('tags.destroy', $tag)" confirm="Delete this tag? Workouts keep existing." />
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">No tags yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
