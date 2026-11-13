<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * true when this set was heavier than every earlier set of the athlete for that exercise.
     * Filled in automatically by WorkoutSetObserver (a model event), never by a form.
     */
    public function up(): void
    {
        Schema::table('workout_sets', function (Blueprint $table) {
            $table->boolean('is_pr')->default(false)->after('weight_kg');
        });
    }

    public function down(): void
    {
        Schema::table('workout_sets', function (Blueprint $table) {
            $table->dropColumn('is_pr');
        });
    }
};
