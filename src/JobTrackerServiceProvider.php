<?php

declare(strict_types=1);

namespace AZirka\JobTracker;

use AZirka\JobTracker\Listeners\JobEventSubscriber;
use AZirka\JobTracker\Services\JobTracker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class JobTrackerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/job-tracker.php', 'job-tracker');

        $this->app->singleton(JobTracker::class);
    }

    public function boot(): void
    {
        Event::subscribe(JobEventSubscriber::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/job-tracker.php' => config_path('job-tracker.php')]);
            $this->publishesMigrations($this->publishableMigrations());

            Factory::guessFactoryNamesUsing(function (string $modelName): string {
                return 'AZirka\\JobTracker\\Database\\Factories\\'.class_basename($modelName).'Factory';
            });
        }
    }

    private function publishableMigrations(): array
    {
        $src = __DIR__.'/../database/migrations';
        $out = [];

        $files = glob($src.'/*.php');
        sort($files);

        $ts = time();
        foreach ($files as $i => $file) {
            $basename = basename($file);
            $clean = preg_replace('/^\d+_/', '', $basename);
            $targetName = date('Y_m_d_His', $ts + $i).'_'.$clean;

            $out[$file] = database_path("migrations/{$targetName}");
        }

        return $out;
    }
}