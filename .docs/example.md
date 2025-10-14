## Simple example how to use

Here is an example of a trackable job:

```bash
<?php

declare(strict_types=1);

namespace App\Jobs;

use Ldi\JobTracker\Contracts\JTTrackableJob;
use Ldi\JobTracker\Traits\JTTracksJobs;

class TrackableJob implements JTTrackableJob
{
    use JTTracksJobs;

    public function handle(): void
    {
        //Do something...
    }
}
```

Here is a simple example of how to start a trackable job in a controller:

```bash
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\TrackableJob;
use Illuminate\Http\JsonResponse;
use Ldi\JobTracker\Models\JTJobGroup;

class ExampleController
{
    public function runTrackableJobs(): JsonResponse
    {
        $jobGroup = JTJobGroup::query()
            ->create([
                'title'         => 'Doing something',
                'time_to_check' => 30,
                'payload'       => [
                    'userId' => auth()->id(),
                ],
            ]);

        TrackableJob::dispatch()->setJobGroupId($jobGroup->id);

        $jobGroup->updateStatusRunning(); //here is the trick

        return response()->noContent();
    }
}
```