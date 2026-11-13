<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'goal', 'bodyweight_kg'])]
// Privacy by design: these fields never end up in arrays/JSON made from a user.
#[Hidden(['password', 'remember_token', 'email', 'bodyweight_kg'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The workouts this user has logged.
     */
    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class);
    }

    /**
     * Every set this user ever did, reached *through* their workouts
     * (users -> workouts -> workout_sets). There is no user_id on workout_sets.
     */
    public function sets(): HasManyThrough
    {
        return $this->hasManyThrough(WorkoutSet::class, Workout::class);
    }
}
