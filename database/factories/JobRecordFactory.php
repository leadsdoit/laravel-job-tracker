<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Database\Factories;

use AZirka\JobTracker\Models\JobGroup;
use AZirka\JobTracker\Models\JobRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobRecordFactory extends Factory
{
    protected $model = JobRecord::class;

    public function definition(): array
    {
        return [
            getForeignIdColumnName(config('job-tracker.tables.groups')) => JobGroup::factory(),
            'uuid'                                                      => fake()->uuid(),
        ];
    }
}