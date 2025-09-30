<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Traits;

use AZirka\JobTracker\Exceptions\JTUninitializedPropertyException;

trait JTTracksJobs
{
    protected ?int $jtJobGroupId = null;

    public function setJobGroupId(int $jobGroupId): static
    {
        $this->jtJobGroupId = $jobGroupId;

        return $this;
    }

    /**
     * @throws JTUninitializedPropertyException
     */
    public function getJobGroupId(): int
    {
        if ($this->jtJobGroupId === null) {
            throw new JTUninitializedPropertyException('jtJobGroupId', static::class);
        }

        return $this->jtJobGroupId;
    }
}