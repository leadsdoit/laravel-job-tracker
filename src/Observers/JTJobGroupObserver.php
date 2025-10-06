<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Observers;

use AZirka\JobTracker\Enum\JTGroupStatus;
use AZirka\JobTracker\Events\JTJobGroupFinished;
use AZirka\JobTracker\Events\JTJobGroupRunning;
use AZirka\JobTracker\Models\JTJobGroup;

class JTJobGroupObserver
{
    public function updated(JTJobGroup $model): void
    {
        if ($model->wasChanged('status')) {
            $event = match ($model->status) {
                JTGroupStatus::AWAITING => new JTJobGroupFinished($model),
                JTGroupStatus::RUNNING  => new JTJobGroupRunning($model),
                default                 => null,
            };

            if ($event !== null) {
                event($event);
            }
        }
    }
}