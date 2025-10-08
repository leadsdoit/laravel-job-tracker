<?php

declare(strict_types=1);

namespace Ldi\Tests\Feature;

use Ldi\JobTracker\Listeners\JobEventSubscriber;
use Ldi\JobTracker\Models\JTJobGroup;
use Ldi\JobTracker\Models\JTJobRecord;
use Ldi\Tests\Support\Job\NotTrackableJob;
use Ldi\Tests\Support\Job\TrackableFailedJob;
use Ldi\Tests\Support\Job\TrackableJob;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\ManuallyFailedException;
use Illuminate\Support\Facades\Event;
use ReflectionFunction;

class JobTrackerIsListeningTest extends AFeatureTestCase
{
    use RefreshDatabase;

    public function test_subscribed_for_listening_job_processing_event(): void
    {
        $this->assertTrue(app('events')->hasListeners(JobProcessing::class), 'JobProcessing event not subscribed');
        $this->assertSubscriberAttached(JobProcessing::class, JobEventSubscriber::class, 'handleJobProcessing');
    }

    public function test_subscribed_for_listening_job_processed_event(): void
    {
        $this->assertTrue(app('events')->hasListeners(JobProcessed::class), 'JobProcessed event not subscribed');
        $this->assertSubscriberAttached(JobProcessed::class, JobEventSubscriber::class, 'handleJobProcessed');
    }

    public function test_subscribed_for_listening_job_failed_event(): void
    {
        $this->assertTrue(app('events')->hasListeners(JobFailed::class), 'JobFailed event not subscribed');
        $this->assertSubscriberAttached(JobFailed::class, JobEventSubscriber::class, 'handleJobFailed');
    }

    public function test_create_and_remove_job_record_for_trackable_job(): void
    {
        $jobGroup = JTJobGroup::factory()->create();

        Event::listen(JobProcessing::class, function (JobProcessing $event) use ($jobGroup): void {
            $this->assertDatabaseHas(
                JTJobRecord::class,
                [
                    getForeignIdColumnName(config('job-tracker.tables.groups')) => $jobGroup->id,
                    'uuid'                                                      => $event->job->uuid(),
                ],
            );
        });
        Event::listen(JobProcessed::class, function (JobProcessed $event) use ($jobGroup): void {
            $this->assertDatabaseMissing(
                JTJobRecord::class,
                [
                    getForeignIdColumnName(config('job-tracker.tables.groups')) => $jobGroup->id,
                    'uuid'                                                      => $event->job->uuid(),
                ],
            );
        });

        $job = (new TrackableJob())->setJobGroupId($jobGroup->id);
        dispatch($job);

        $this->assertDatabaseCount(JTJobRecord::class, 0);
    }

    public function test_not_trackable_job_is_ignored(): void
    {
        Event::listen(JobProcessing::class, function (JobProcessing $event): void {
            $this->assertDatabaseMissing(JTJobRecord::class, ['uuid' => $event->job->uuid()]);
        });

        dispatch(new NotTrackableJob);
    }

    public function test_create_and_remove_job_record_for_trackable_failed_job(): void
    {
        $jobGroup = JTJobGroup::factory()->create();

        Event::listen(JobProcessing::class, function (JobProcessing $event) use ($jobGroup): void {
            $this->assertDatabaseHas(
                JTJobRecord::class,
                [
                    getForeignIdColumnName(config('job-tracker.tables.groups')) => $jobGroup->id,
                    'uuid'                                                      => $event->job->uuid(),
                ],
            );
        });
        Event::listen(JobFailed::class, function (JobFailed $event) use ($jobGroup): void {
            $this->assertDatabaseMissing(
                JTJobRecord::class,
                [
                    getForeignIdColumnName(config('job-tracker.tables.groups')) => $jobGroup->id,
                    'uuid'                                                      => $event->job->uuid(),
                ],
            );
        });

        $this->expectException(ManuallyFailedException::class);
        $this->expectExceptionMessage('Manually failed exception');

        $job = (new TrackableFailedJob())->setJobGroupId($jobGroup->id);
        dispatch($job);

        $this->assertDatabaseCount(JTJobRecord::class, 0);
    }

    private function assertSubscriberAttached(string $eventClass, string $subscriberClass, string $method): void
    {
        $listeners = app('events')->getListeners($eventClass);

        $found = collect($listeners)->contains(function ($listener) use ($subscriberClass, $method) {
            if (is_array($listener)) {
                return ($listener[0] ?? null) === $subscriberClass
                    && ($listener[1] ?? null) === $method;
            }

            if ($listener instanceof Closure) {
                $rf = new ReflectionFunction($listener);
                $vars = $rf->getStaticVariables();
                $wrapped = $vars['listener'] ?? null;

                // 'Class@method'
                if (is_string($wrapped) && str_contains($wrapped, '@')) {
                    [$cls, $meth] = explode('@', $wrapped, 2);
                    return $cls === $subscriberClass && $meth === $method;
                }

                // __invoke
                if (is_string($wrapped)) {
                    return $wrapped === $subscriberClass && $method === '__invoke';
                }

                //[Class, 'method']
                if (is_array($wrapped)) {
                    return ($wrapped[0] ?? null) === $subscriberClass
                        && ($wrapped[1] ?? null) === $method;
                }
            }

            return false;
        });

        $this->assertTrue($found, "Subscriber {$subscriberClass}@{$method} not attached to {$eventClass}");
    }
}