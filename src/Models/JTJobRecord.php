<?php

declare(strict_types=1);

namespace AZirka\JobTracker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JTJobRecord extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('job-tracker.tables.jobs');
    }
    protected $fillable = ['uuid'];
    public $timestamps = false;

    public static function findOrCreate(int $jobGroupId, string $uuid): static
    {
        return static::firstOrCreate([
            getForeignIdColumnName(config('job-tracker.tables.groups')) => $jobGroupId,
            'uuid'                                                      => $uuid,
        ]);
    }

    public static function deleteByGroupAndUuid(int $jobGroupId, string $uuid): int
    {
        return static::query()
            ->where(getForeignIdColumnName(config('job-tracker.tables.groups')), $jobGroupId)
            ->where('uuid', $uuid)
            ->delete();
    }

    public function getFillable(): array
    {
        return [...parent::getFillable(), getForeignIdColumnName(config('job-tracker.tables.groups'))];
    }

    public function getTable(): string
    {
        return config('job-tracker.tables.jobs');
    }

    public function jobGroup(): BelongsTo
    {
        return $this->belongsTo(JTJobGroup::class, getForeignIdColumnName(config('job-tracker.tables.groups')), 'id');
    }
}