<?php

declare(strict_types=1);

namespace AZirka\JobTracker;

use Illuminate\Support\ServiceProvider;

class JobTrackerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/job-tracker.php', 'job-tracker');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/job-tracker.php' => config_path('job-tracker.php')]);
            $this->publishesMigrations([__DIR__.'/../database/migrations' => database_path('migrations')]);
        }
    }
}