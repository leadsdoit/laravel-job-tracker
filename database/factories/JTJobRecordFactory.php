<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Database\Factories;

use AZirka\JobTracker\Models\JTJobGroup;
use AZirka\JobTracker\Models\JTJobRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class JTJobRecordFactory extends Factory
{
    protected $model = JTJobRecord::class;

    public function definition(): array
    {
        return [
            getForeignIdColumnName(config('job-tracker.tables.groups')) => JTJobGroup::factory(),
            'uuid'                                                      => fake()->uuid(),
        ];
    }
}