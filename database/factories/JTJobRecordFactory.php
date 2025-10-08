<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Database\Factories;

use Ldi\JobTracker\Models\JTJobGroup;
use Ldi\JobTracker\Models\JTJobRecord;
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