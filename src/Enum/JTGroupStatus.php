<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Enum;
use Ldi\JobTracker\Traits\Enum\EnumValues;

enum JTGroupStatus: string
{
    use EnumValues;

    case AWAITING = 'awaiting';
    case RUNNING = 'running';
}
