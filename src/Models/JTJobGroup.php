<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Models;

use AZirka\JobTracker\Builders\JobGroupBuilder;
use AZirka\JobTracker\Enum\JTGroupStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JTJobGroup extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('job-tracker.tables.groups');
        parent::__construct($attributes);
    }

    protected $fillable = [
        'title',
        'status',
        'time_to_check',
        'next_check_at',
        'number_job_last_check',
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
        static::creating(function (JTJobGroup $model) {
            if (is_null($model->next_check_at)) {
                $ttc = $model->time_to_check ?? config('job-tracker.ttc');
                $model->next_check_at = now()->addSeconds($ttc);
            }
        });

        static::updating(function (JTJobGroup $model) {
            if ($model->isDirty('time_to_check') && is_null($model->next_check_at)) {
                $model->next_check_at = now()->addSeconds($model->time_to_check);
            }
        });
    }

    public function newEloquentBuilder($query): JobGroupBuilder
    {
        return new JobGroupBuilder($query);
    }

    public function jobRecords(): HasMany
    {
        return $this->hasMany(JTJobRecord::class, getForeignIdColumnName(config('job-tracker.tables.groups')), 'id');
    }

    public function increaseNextCheckAt(): self
    {
        $this->next_check_at = now()->addSeconds($this->time_to_check);

        return $this;
    }

    public function updateStatusAwaiting(): self
    {
        $this->update(['status' => JTGroupStatus::AWAITING]);

        return $this;
    }

    public function updateStatusRunning(): self
    {
        $this->update(['status' => JTGroupStatus::RUNNING]);

        return $this;
    }
}