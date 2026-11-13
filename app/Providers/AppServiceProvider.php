<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use resources/views/pagination.blade.php instead of Laravel's Tailwind version.
        Paginator::defaultView('pagination');

        // N+1 guard: outside production, loading a relationship inside a loop without with()
        // throws an error immediately, so the problem is found while developing, not by users.
        Model::preventLazyLoading(! $this->app->isProduction());
    }
}
