<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blotter_records', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('complaint_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by_id')->constrained('users')->cascadeOnDelete();
            $table->string('incident_type');
            $table->text('narrative');
            $table->string('location');
            $table->dateTime('incident_at');
            $table->json('parties_involved')->nullable();    // [{name, role}]
            $table->enum('status', ['open', 'investigating', 'closed', 'forwarded'])->default('open')->index();
            $table->text('investigation_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mediation_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->string('venue');
            $table->foreignId('assigned_officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['scheduled', 'completed', 'rescheduled', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('outcome')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['complaint_id', 'scheduled_at']);
        });

        Schema::create('hearing_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->string('venue');
            $table->foreignId('assigned_officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['scheduled', 'completed', 'rescheduled', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('outcome')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['complaint_id', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hearing_schedule');
        Schema::dropIfExists('mediation_schedule');
        Schema::dropIfExists('blotter_records');
    }
};
