<?php

declare(strict_types=1);

namespace Database\Factories;

use Ldi\JobTracker\Enum\JTGroupStatus;
use Ldi\JobTracker\Models\JTJobGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class JTJobGroupFactory extends Factory
{
    protected $model = JTJobGroup::class;

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