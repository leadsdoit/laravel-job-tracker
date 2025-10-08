<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Contracts;

use Ldi\JobTracker\Exceptions\JTUninitializedPropertyException;

interface JTTrackableJob
{
    public function setJobGroupId(int $jobGroupId): static;

    /**
     * @throws JTUninitializedPropertyException
     */
    public function getJobGroupId(): int;
}