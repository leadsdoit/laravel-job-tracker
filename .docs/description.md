
## Description how it works

### Contracts

Trackable job should implement the `Ldi\JobTracker\Contracts\TrackableJob` interface. For default implementation you can
use the `Ldi\JobTracker\Traits\TracksJobs` trait.

Trackable job should belong to a job group model. So before dispatching a trackable job, you need to create one and
assign it `id` to the trackable job. When creating a job group, you can configure some useful `payload` or
`time_to_check` to determine the frequency of group checking also you can run several different types of jobs by
combining them into one group.

The first and most flexible way is using the `setJobGroupId` method of the `Ldi\JobTracker\Contracts\TrackableJob`
interface when dispatching the job.

```bash

    TrackableJob::dispatch()->setJobGroupId($jobGroupId);
```

Another way to get a group when initializing a job.

```bash

    public function __construct()
    {
        $jobGroup = JTJobGroup::query()->firstOrCreate(['title' => 'Some job group title from preset']);
        $this->setJobGroupId($jobGroup->id);
    }
```

Or you can to pre-create the necessary groups in some application seeder and use their `ids` like hardcode inside a job)

### Exceptions

If the job is processing before the property is initialized, an exception
`Ldi\JobTracker\Exceptions\JTUninitializedPropertyException` will be thrown.

### Events

Every minute is started `Ldi\JobTracker\Commands\JTCheckGroupCommand` console command that checks the status of the job
group. When the status of the job group is changes, an event is dispatched. Event contains the property `$jobGroup`,
which contains an instance of the group for which the event was triggered. Exist two events:

- `Ldi\JobTracker\Events\JTJobGroupStarted` when has detected at least one task belonging to the group and which is
  being processed
- `Ldi\JobTracker\Events\JTJobGroupCompleted` when all jobs in the group are completed (if some jos is failed it is
  equivalent to complete)

Since cron runs once a minute, there's a situation where all tasks might be completed before it runs, and the group
status might not change, meaning events won't be triggered. To work around this, you can update the status using the
`updateStatusRunning` method of the `Ldi\JobTracker\Models\JTJobGroup` model immediately after submitting tasks. This
way both events are guaranteed to work.

See the [Simple example how to use](/.docs/example.md)