<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Traits\Enum;

trait EnumValues
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
