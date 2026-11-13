<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Weight;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * A training report in the terminal:  php artisan gymlog:weekly-summary
 * Options:  --days=14  (look further back)
 */
#[Signature('gymlog:weekly-summary {--days=7 : How many days to look back}')]
#[Description('Show how much every athlete trained in the last days')]
class WeeklySummary extends Command
{
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $since = today()->subDays($days - 1);

        // Every athlete with their sets from the period (eager loaded: one query per relationship).
        $athletes = User::with(['sets' => fn ($query) => $query->where('workouts.date', '>=', $since)])
            ->withCount(['workouts' => fn ($query) => $query->where('date', '>=', $since)])
            ->orderBy('name')
            ->get()
            ->filter(fn (User $user) => $user->workouts_count > 0);

        if ($athletes->isEmpty()) {
            $this->warn("Nobody trained in the last {$days} days.");

            return self::SUCCESS;
        }

        $this->info("Training in the last {$days} days (since {$since->format('D j M')})");

        $this->table(
            ['Athlete', 'Workouts', 'Sets', 'Volume', 'New records'],
            $athletes->map(fn (User $user) => [
                $user->name,
                $user->workouts_count,
                $user->sets->count(),
                Weight::format($user->sets->sum(fn ($set) => $set->reps * $set->weight_kg), 'kg', 0),
                $user->sets->where('is_pr', true)->count(),
            ]),
        );

        return self::SUCCESS;
    }
}
