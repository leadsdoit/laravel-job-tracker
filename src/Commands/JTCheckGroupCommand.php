<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Commands;

use AZirka\JobTracker\Services\JobGroupMonitorService;
use Illuminate\Console\Command;

class JTCheckGroupCommand extends Command
{
    protected $signature = 'jt:check-groups';
    protected $description = 'Check job groups, update current status and job records number';

    public function handle(JobGroupMonitorService $service): int
    {
        $this->info($this->getLogMessage());

        $service->checkGroups();

        return static::SUCCESS;
    }

    protected function getLogMessage(): string
    {
        return 'Started: '.$this->description.' at '.now()->toDateTimeString();
    }
}