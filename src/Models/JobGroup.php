<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Models;

use AZirka\JobTracker\Enum\JTGroupStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'time_to_check',
        'next_check_at',
        'payload',
        'description',
    ];

    protected $casts = [
        'status'        => JTGroupStatus::class,
        'next_check_at' => 'datetime',
        'payload'       => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (JobGroup $model) {
            if (is_null($model->next_check_at)) {
                $ttc = $model->time_to_check ?? config('job-tracker.ttc');
                $model->next_check_at = now()->addSeconds($ttc);
            }
        });

        static::updating(function (JobGroup $model) {
            if ($model->isDirty('time_to_check') && is_null($model->next_check_at)) {
                $model->next_check_at = now()->addSeconds($model->time_to_check);
            }
        });
    }

    public function getTable(): string
    {
        return config('job-tracker.tables.groups');
    }

    public function jobRecords(): HasMany
    {
        return $this->hasMany(JobRecord::class, getForeignIdColumnName(config('job-tracker.tables.groups')), 'id');
    }
}