<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Enum;
use AZirka\JobTracker\Traits\Enum\EnumValues;

enum JTGroupStatus: string
{
    use EnumValues;

    case NEW = 'new';
    case RUNNING = 'running';
}
