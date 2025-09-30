<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Listeners;

use AZirka\JobTracker\Services\JobTrackDecider;
use AZirka\JobTracker\Services\JobTracker;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

readonly class JobEventSubscriber
{
    public function __construct(
        private JobTracker      $jobTracker,
        private JobTrackDecider $jobTrackDecider,
    ) {
    }

    public function handleJobProcessing(JobProcessing $event): void
    {
        if ($this->jobTrackDecider->isJobTrackable($event->job)) {
            $this->jobTracker->track($event);
        }
    }

    public function handleJobProcessed(JobProcessed $event): void
    {
        if ($this->jobTrackDecider->isJobTrackable($event->job)) {
            $this->jobTracker->unTrack($event);
        }
    }

    public function handleJobFailed(JobFailed $event): void
    {
        if ($this->jobTrackDecider->isJobTrackable($event->job)) {
            $this->jobTracker->unTrack($event);
        }
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(JobProcessing::class, [JobEventSubscriber::class, 'handleJobProcessing']);
        $events->listen(JobProcessed::class, [JobEventSubscriber::class, 'handleJobProcessed']);
        $events->listen(JobFailed::class, [JobEventSubscriber::class, 'handleJobFailed']);
    }
}
