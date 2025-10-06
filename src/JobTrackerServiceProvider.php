<?php

declare(strict_types=1);

namespace AZirka\JobTracker;

use AZirka\JobTracker\Commands\JTCheckGroupCommand;
use AZirka\JobTracker\Listeners\JobEventSubscriber;
use AZirka\JobTracker\Models\JTJobGroup;
use AZirka\JobTracker\Observers\JTJobGroupObserver;
use AZirka\JobTracker\Services\JobTracker;
use Illuminate\Console\Scheduling\Schedule;
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
        if (!config('job-tracker.enabled')) {
            return;
        }

        JTJobGroup::observe(JTJobGroupObserver::class);
        Event::subscribe(JobEventSubscriber::class);

        if ($this->app->runningInConsole()) {
            $this->bootCommands();
            $this->bootSchedule();
            $this->bootPublishable();
            $this->bootFactories();
        }
    }

    private function bootCommands(): void
    {
        $this->commands([
            JTCheckGroupCommand::class,
        ]);
    }

    private function bootSchedule(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(JTCheckGroupCommand::class)->everyMinute();
        });
    }

    private function bootPublishable(): void
    {
        $this->publishes([__DIR__.'/../config/job-tracker.php' => config_path('job-tracker.php')]);
        $this->publishesMigrations($this->publishableMigrations());
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

    private function bootFactories(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName): string {
            return 'AZirka\\JobTracker\\Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}