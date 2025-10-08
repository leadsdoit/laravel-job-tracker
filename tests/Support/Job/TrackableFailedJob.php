<?php

declare(strict_types=1);

namespace Ldi\Tests\Support\Job;

use Ldi\JobTracker\Contracts\JTTrackableJob;
use Ldi\JobTracker\Traits\JTTracksJobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\ManuallyFailedException;

class TrackableFailedJob implements ShouldQueue, JTTrackableJob
{
    use Dispatchable;
    use Queueable;
    use JTTracksJobs;

    public function handle(): void
    {
        print_r(PHP_EOL.'Start trackable job. Do fail...');
        throw new ManuallyFailedException('Manually failed exception');
    }
}