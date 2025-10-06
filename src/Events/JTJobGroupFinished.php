<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Events;

use AZirka\JobTracker\Models\JTJobGroup;
use Illuminate\Foundation\Events\Dispatchable;

class JTJobGroupFinished
{
    use Dispatchable;

    public function __construct(public readonly JTJobGroup $jobGroup)
    {
    }
}
