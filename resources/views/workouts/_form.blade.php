{{-- The workout always belongs to the logged-in athlete, so there is no "athlete" field. --}}
<x-input name="title" label="Title" :value="$workout->title" required maxlength="100" placeholder="e.g. Leg day" />
<x-input name="date" label="Date" type="date" :value="$workout->date?->format('Y-m-d')" required />
<x-input name="duration_minutes" label="Duration in minutes (optional)" type="number" min="1" max="600"
         :value="$workout->duration_minutes" />
<x-textarea name="notes" label="Notes (optional)" :value="$workout->notes" />

{{-- N-M: tick any number of tags. old('tags', ...) keeps the ticks after a validation error. --}}
@php($selectedTags = old('tags', $workout->exists ? $workout->tags->pluck('id')->all() : []))
<fieldset @class(['field', 'has-error' => $errors->has('tags') || $errors->has('tags.*')])>
    <legend>Tags (optional)</legend>
    <div class="tag-picker">
        @foreach ($tags as $tag)
            <label class="check">
                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedTags))>
                {{ $tag->name }}
            </label>
        @endforeach
    </div>
    <x-error name="tags" />
    <x-error name="tags.*" />
</fieldset>

{{-- Private: the current photo is loaded through the protected workouts.photo route. --}}
<x-image-upload name="photo" label="Progress photo (optional, private, max 4 MB)"
                :current="$workout->photo_path ? route('workouts.photo', $workout) : null" />
