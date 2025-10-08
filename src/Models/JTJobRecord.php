<?php

declare(strict_types=1);

namespace Ldi\JobTracker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JTJobRecord extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('job-tracker.tables.jobs');
        $this->primaryKey = null;
        $this->timestamps = false;
        $this->incrementing = false;
        $this->guarded = [];

        parent::__construct($attributes);
    }

    public static function findOrCreate(int $jobGroupId, string $uuid): static
    {
        $model = static::query()
            ->where(getForeignIdColumnName(config('job-tracker.tables.groups')), $jobGroupId)
            ->where('uuid', $uuid)
            ->first();

        if ($model) {
            return $model;
        }

        $model = new static([
            getForeignIdColumnName(config('job-tracker.tables.groups')) => $jobGroupId,
            'uuid'                                                      => $uuid,
        ]);

        $model->save();

        return $model;
    }

    public static function deleteByGroupAndUuid(int $jobGroupId, string $uuid): int
    {
        return static::query()
            ->where(getForeignIdColumnName(config('job-tracker.tables.groups')), $jobGroupId)
            ->where('uuid', $uuid)
            ->delete();
    }

    public static function deleteByUuid(string $uuid): int
    {
        return static::query()->where('uuid', $uuid)->delete();
    }

    public function jobGroup(): BelongsTo
    {
        return $this->belongsTo(JTJobGroup::class, getForeignIdColumnName(config('job-tracker.tables.groups')), 'id');
    }
}