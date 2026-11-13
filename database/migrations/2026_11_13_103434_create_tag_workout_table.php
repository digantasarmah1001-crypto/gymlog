<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot table for the many-to-many between tags and workouts.
     * Laravel's naming rule: both model names, singular, alphabetical, joined with "_" -> tag_workout.
     */
    public function up(): void
    {
        Schema::create('tag_workout', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workout_id')->constrained()->cascadeOnDelete();
            $table->primary(['tag_id', 'workout_id']); // the same tag can't be on a workout twice
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_workout');
    }
};
