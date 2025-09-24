<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Enum;

enum JTGroupStatus: string
{
    case NEW = 'new';
    case RUNNING = 'running';
}
