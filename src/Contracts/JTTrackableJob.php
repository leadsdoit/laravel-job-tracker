<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Contracts;

interface JTTrackableJob
{
    public function setJobGroupId(int $jobGroupId): void;

    public function getJobGroupId(): int;
}