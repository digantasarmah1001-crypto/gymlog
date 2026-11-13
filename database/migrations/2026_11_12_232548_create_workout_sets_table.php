<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workout_sets', function (Blueprint $table) {
            $table->id();
            // A set belongs to one workout and is one exercise (the two 1-N relationships).
            $table->foreignId('workout_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('set_number');
            $table->unsignedSmallInteger('reps');
            // decimal (not float) so 82.5 kg is stored exactly; max 9999.99.
            $table->decimal('weight_kg', 6, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_sets');
    }
};
