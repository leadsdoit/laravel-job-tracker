<?php

declare(strict_types=1);

namespace AZirka\Tests\Support\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotTrackableJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function handle(): void
    {
        print_r(PHP_EOL.'Start not trackable job. Do something... ');
        sleep(fake()->numberBetween(0, 2));
        print_r('Finish not trackable job'.PHP_EOL);
    }
}