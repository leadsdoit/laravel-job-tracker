<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;

class JobTrackerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/job-tracker.php', 'job-tracker');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/job-tracker.php' => config_path('job-tracker.php'),
        ]);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);
    }
}