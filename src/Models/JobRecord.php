<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
    ];

    public function getFillable(): array
    {
        return [...parent::getFillable(), [getForeignIdColumnName(config('job-tracker.tables.groups'))]];
    }

    public function getTable(): string
    {
        return config('job-tracker.tables.jobs');
    }

    public function jobGroup(): BelongsTo
    {
        return $this->belongsTo(JobGroup::class, getForeignIdColumnName(config('job-tracker.tables.groups')), 'id');
    }
}