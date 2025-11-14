<?php

declare(strict_types=1);

namespace Tests\Feature;

use Ldi\JobTracker\Models\JTJobGroup;
use Carbon\Carbon;

class JobGroupTest extends AFeatureTestCase
{
    public function test_create_job_groups(): void
    {
        $number = 5;

        JTJobGroup::factory()->createMany($number);

        $jobGroups = JTJobGroup::all();

        $this->assertEquals($number, $jobGroups->count(), 'Created Job group count should be '.$number);
    }

    public function test_next_check_at_updates_when_time_to_check_changes(): void
    {
        Carbon::setTestNow('2025-01-01 12:00:00');

        $jobGroup = JTJobGroup::factory()->create([
            'time_to_check' => 60,
            'next_check_at' => Carbon::parse('2025-01-01 12:01:00'),
        ]);
        $originalNextCheckAt = $jobGroup->next_check_at;

        Carbon::setTestNow('2025-01-01 12:30:00');

        $jobGroup->update(['time_to_check' => 120, 'next_check_at' => null]);
        $jobGroup->refresh();

        $expectedNextCheckAt = Carbon::parse('2025-01-01 12:32:00');

        $this->assertEquals($expectedNextCheckAt, $jobGroup->next_check_at);
        $this->assertNotEquals($originalNextCheckAt, $jobGroup->next_check_at);
        $this->assertEquals(120, $jobGroup->time_to_check);
    }

    public function test_next_check_at_does_not_change_when_time_to_check_changes_but_next_check_at_is_set(): void
    {
        Carbon::setTestNow('2025-01-01 12:00:00');

        $jobGroup = JTJobGroup::factory()->create([
            'time_to_check' => 60,
            'next_check_at' => Carbon::parse('2025-01-01 15:00:00'),
        ]);

        $originalNextCheckAt = $jobGroup->next_check_at;

        $jobGroup->update(['time_to_check' => 120]);
        $jobGroup->refresh();

        $this->assertEquals($originalNextCheckAt, $jobGroup->next_check_at);
        $this->assertEquals(120, $jobGroup->time_to_check);
    }

    public function test_next_check_at_updates_when_only_time_to_check_changes_and_next_check_at_is_null(): void
    {
        Carbon::setTestNow('2025-01-01 12:00:00');

        $jobGroup = JTJobGroup::factory()->create([
            'time_to_check' => 60,
            'next_check_at' => Carbon::parse('2025-01-01 12:01:00'),
        ]);

        Carbon::setTestNow('2025-01-01 13:00:00');

        $jobGroup->time_to_check = 300;
        $jobGroup->next_check_at = null;
        $jobGroup->save();
        $jobGroup->refresh();

        $expectedNextCheckAt = Carbon::parse('2025-01-01 13:05:00');
        $this->assertEquals($expectedNextCheckAt, $jobGroup->next_check_at);
        $this->assertEquals(300, $jobGroup->time_to_check);
    }

    public function test_next_check_at_does_not_update_when_time_to_check_not_changed(): void
    {
        Carbon::setTestNow('2025-01-01 12:00:00');

        $jobGroup = JTJobGroup::factory()->create([
            'title'         => 'Original Title',
            'time_to_check' => 60,
            'next_check_at' => Carbon::parse('2025-01-01 15:00:00'),
        ]);

        $jobGroup->update(['title' => 'Updated Title', 'next_check_at' => null]);
        $jobGroup->refresh();

        $this->assertNull($jobGroup->next_check_at);
        $this->assertEquals('Updated Title', $jobGroup->title);
        $this->assertEquals(60, $jobGroup->time_to_check);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }
}