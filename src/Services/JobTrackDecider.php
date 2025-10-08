<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Services;

use Ldi\JobTracker\Contracts\JTTrackableJob;
use Illuminate\Contracts\Queue\Job;

final class JobTrackDecider
{
    public function isJobTrackable(Job $job): bool
    {
        $class = $this->resolveJobClass($job);
        if ($class !== null) {
            return is_subclass_of($class, JTTrackableJob::class);
        }

        return false;
    }

    public function resolveJobClass(Job $job): ?string
    {
        if (method_exists($job, 'resolveName')) {
            $name = $job->resolveName();
            if ($name === 'Illuminate\Queue\CallQueuedClosure') {
                return null;
            }
            return class_exists($name) ? $name : null;
        }

        $payload = $job->payload();
        if (!empty($payload['data']['commandName']) && class_exists($payload['data']['commandName'])) {
            return $payload['data']['commandName'];
        }

        if (!empty($payload['displayName']) && class_exists($payload['displayName'])) {
            return $payload['displayName'];
        }

        return null;
    }
}