{{-- Temporary until session 7: the athlete is chosen here instead of using the logged-in user. --}}
<x-select name="user_id" label="Athlete" :options="$athletes" :value="$workout->user_id" required />
<x-input name="title" label="Title" :value="$workout->title" required maxlength="100" placeholder="e.g. Leg day" />
<x-input name="date" label="Date" type="date" :value="$workout->date?->format('Y-m-d')" required />
<x-input name="duration_minutes" label="Duration in minutes (optional)" type="number" min="1" max="600"
         :value="$workout->duration_minutes" />
<x-textarea name="notes" label="Notes (optional)" :value="$workout->notes" />
