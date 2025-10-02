<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Services;

use AZirka\JobTracker\Contracts\JTTrackableJob;
use AZirka\JobTracker\Models\JTJobRecord;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class JobTracker
{
    public function track(JobProcessing $event): void
    {
        $instance = $this->getRealJobInstance($event);

        if ($instance instanceof JTTrackableJob) {
            $jobGroupId = $instance->getJobGroupId();
            JTJobRecord::findOrCreate($jobGroupId, $event->job->uuid());
        }
    }

    public function unTrack(JobProcessed|JobFailed $event): void
    {
        $instance = $this->getRealJobInstance($event);

        if ($instance instanceof JTTrackableJob) {
            $jobGroupId = $instance->getJobGroupId();
            JTJobRecord::deleteByGroupAndUuid($jobGroupId, $event->job->uuid());
        }
    }

    private function getRealJobInstance(JobProcessing|JobProcessed|JobFailed $event): ?object
    {
        $command = $event->job->payload()['data']['command'] ?? null;
        return $command ? unserialize($command) : null;
    }
}