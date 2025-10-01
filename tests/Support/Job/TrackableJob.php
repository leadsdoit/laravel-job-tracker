<?php

declare(strict_types=1);

namespace AZirka\Tests\Support\Job;

use AZirka\JobTracker\Contracts\JTTrackableJob;
use AZirka\JobTracker\Traits\JTTracksJobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TrackableJob implements ShouldQueue, JTTrackableJob
{
    use Dispatchable;
    use Queueable;
    use JTTracksJobs;

    public function handle(): void
    {
        print_r(PHP_EOL.'Start trackable job. Do something... ');
        sleep(fake()->numberBetween(0, 2));
        print_r('Finish trackable job'.PHP_EOL);
    }
}