<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Services;

use AZirka\JobTracker\Builders\JobGroupBuilder;
use AZirka\JobTracker\Models\JTJobGroup;
use Illuminate\Database\Eloquent\Collection;

class JobGroupMonitorService
{
    public function checkGroups(): void
    {
        $groups = $this->getGroupsDueToCheck();
        $groups->each(fn(JTJobGroup $group) => $this->countGroupJobRecordsAndUpdateStatus($group));
    }

    protected function getGroupsDueToCheck(): Collection
    {
        /** @var JobGroupBuilder $builder */
        $builder = JTJobGroup::query();
        return $builder->whereNextCheckBefore()->get();
    }

    public function countGroupJobRecordsAndUpdateStatus(JTJobGroup $group): void
    {
        $countJobRecords = $group->jobRecords()->count();

        $group->increaseNextCheckAt();
        $group->number_job_last_check = $countJobRecords;

        if ($countJobRecords > 0) {
            $group->updateStatusRunning();
        } elseif ($countJobRecords === 0) {
            $group->updateStatusAwaiting();
        }
    }
}