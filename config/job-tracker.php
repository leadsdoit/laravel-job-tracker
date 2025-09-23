<?php

declare(strict_types=1);

return [
    /** Time to check jobs status in seconds (default: 60) */
    'ttc' => env('AZIRKA_JOB_TRACKER_TTC', 60),

    /** Table names */
    'tables' => [
        'groups' => 'jt_job_groups',
        'jobs'   => 'jt_job_records',
    ],
];