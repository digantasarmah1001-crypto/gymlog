<x-select name="exercise_id" label="Exercise" :options="$exercises" :value="$set->exercise_id" required />
<x-input name="set_number" label="Set number" type="number" min="1" max="50" :value="$set->set_number" required />
<x-input name="reps" label="Reps" type="number" min="1" max="100" :value="$set->reps" required />
<x-input name="weight_kg" label="Weight (kg)" type="number" min="0" max="1000" step="0.25" :value="$set->weight_kg" required />
