<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Traits;

use Exception;

trait JTTracksJobs
{
    protected ?int $jtJobGroupId = null;

    public function setJobGroupId(int $jobGroupId): void
    {
        $this->jtJobGroupId = $jobGroupId;
    }

    /**
     * @throws Exception
     */
    public function getJobGroupId(): int
    {
        if (is_null($this->jtJobGroupId)) {
            throw new Exception(
                'Job group id is not set, call method setJobGroupId() while dispatching the job or in the constructor'
            );
        }

        return $this->jtJobGroupId;
    }
}