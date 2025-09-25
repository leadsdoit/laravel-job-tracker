<?php

declare(strict_types=1);

use AZirka\JobTracker\Enum\JTGroupStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(config('job-tracker.tables.groups'), function (Blueprint $table): void {
            $table->id();
            $table->string('title')->unique();
            $table->string('status')->default(JTGroupStatus::NEW->value);
            $table->integer('time_to_check')->default(config('job-tracker.ttc'));
            $table->dateTime('next_check_at')->nullable();
            $table->jsonb('payload')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('job-tracker.tables.groups'));
    }
};
