<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('basic')
            );
        });

        SecurityScheme::http('basic');
        Gate::define('viewApiDocs', function (User $user) {
            return true;
        });

        // Add scheduling
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            
            // Weekly full report - run every Monday at 7 AM
            $schedule->command('app:send-balance-report')
                    ->weekly()
                    ->mondays()
                    ->at('07:00')
                    ->withoutOverlapping()
                    ->runInBackground();

            // Hourly balance monitoring
            $schedule->command('app:monitor-balances')
                    ->hourly()
                    ->withoutOverlapping()
                    ->runInBackground();
        });
    }
}