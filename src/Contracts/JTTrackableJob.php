<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Contracts;

use AZirka\JobTracker\Exceptions\JTUninitializedPropertyException;

interface JTTrackableJob
{
    public function setJobGroupId(int $jobGroupId): static;

    /**
     * @throws JTUninitializedPropertyException
     */
    public function getJobGroupId(): int;
}