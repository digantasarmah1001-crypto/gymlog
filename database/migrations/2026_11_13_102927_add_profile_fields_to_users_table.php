<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profile fields for athletes. Both are optional.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('goal', 120)->nullable()->after('email');          // public, e.g. "Squat 140 kg"
            $table->decimal('bodyweight_kg', 5, 1)->nullable()->after('goal'); // private: only the owner sees it
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['goal', 'bodyweight_kg']);
        });
    }
};
