<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Builders;

use Ldi\JobTracker\Enum\JTGroupStatus;
use Illuminate\Database\Eloquent\Builder;

class JobGroupBuilder extends Builder
{
    public function whereStatus(JTGroupStatus $status): self
    {
        return $this->where('status', '=', $status->value);
    }

    public function whereStatusAwaiting(): self
    {
        return $this->whereStatus(JTGroupStatus::AWAITING);
    }

    public function whereStatusRunning(): self
    {
        return $this->whereStatus(JTGroupStatus::RUNNING);
    }

    public function whereNextCheckBetween(string $fromAt, string $toAt, bool $isInclude = true): self
    {
        return $this->whereNextCheckAfter($fromAt, $isInclude)->whereNextCheckBefore($toAt, $isInclude);
    }

    public function whereNextCheckAfter(?string $fromAt = null, bool $isInclude = true): self
    {
        if ($fromAt === null) {
            $fromAt = now()->format('Y-m-d H:i:s');
        }

        return $this->where('next_check_at', $isInclude ? '>=' : '>', $fromAt);
    }

    public function whereNextCheckBefore(?string $toAt = null, bool $isInclude = true): self
    {
        if ($toAt === null) {
            $toAt = now()->format('Y-m-d H:i:s');
        }

        return $this->where('next_check_at', $isInclude ? '<=' : '<', $toAt);
    }
}
