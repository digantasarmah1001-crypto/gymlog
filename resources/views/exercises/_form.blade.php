<x-input name="name" label="Name" :value="$exercise->name" required maxlength="100" />
<x-select name="muscle_group" label="Muscle group"
          :options="array_combine(App\Models\Exercise::MUSCLE_GROUPS, App\Models\Exercise::MUSCLE_GROUPS)"
          :value="$exercise->muscle_group" required />
<x-input name="equipment" label="Equipment (optional)" :value="$exercise->equipment" placeholder="e.g. Barbell" />
<x-textarea name="description" label="Description (optional)" :value="$exercise->description" />
<x-image-upload name="image" label="Photo (optional, max 2 MB)" :current="$exercise->imageUrl()" />
