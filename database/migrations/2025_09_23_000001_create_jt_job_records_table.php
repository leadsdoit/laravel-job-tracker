<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(config('job-tracker.tables.jobs'), function (Blueprint $table): void {
            $groupTableName = config('job-tracker.tables.groups');

            $foreignKeyName = str_ends_with($groupTableName, 's')
                ? substr($groupTableName, 0, -1).'_id'
                : $groupTableName.'_id';

            $table->foreignId($foreignKeyName)->constrained($groupTableName)->cascadeOnDelete();
            $table->uuid();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('job-tracker.tables.jobs'));
    }
};
