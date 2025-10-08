<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Exceptions;

use LogicException;

class JTUninitializedPropertyException extends LogicException
{
    public function __construct(string $property, string $className)
    {
        parent::__construct("Property $property is not initialized in $className.");
    }
}