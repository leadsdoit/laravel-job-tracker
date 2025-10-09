<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Observers;

use Ldi\JobTracker\Enum\JTGroupStatus;
use Ldi\JobTracker\Events\JTJobGroupFinished;
use Ldi\JobTracker\Events\JTJobGroupRunning;
use Ldi\JobTracker\Models\JTJobGroup;

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