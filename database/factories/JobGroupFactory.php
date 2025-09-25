<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Database\Factories;

use AZirka\JobTracker\Enum\JTGroupStatus;
use AZirka\JobTracker\Models\JobGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobGroupFactory extends Factory
{
    protected $model = JobGroup::class;

    public function definition(): array
    {
        return [
            'title'         => fake()->numerify('title-########'),
            'status'        => fake()->randomElement(JTGroupStatus::values()),
            'time_to_check' => fake()->numberBetween(1, 600),
            'next_check_at' => null,
            'payload'       => null,
            'description'   => fake()->boolean() ? fake()->text : null,
        ];
    }
}