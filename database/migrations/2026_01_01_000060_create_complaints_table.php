<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('complainant_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('category');                      // from config('panipone.complaint_categories')
            $table->string('custom_category')->nullable();
            $table->string('respondent_name');
            $table->date('incident_date');
            $table->string('incident_location');
            $table->text('description');
            $table->string('witness_name')->nullable();
            $table->string('witness_contact', 20)->nullable();

            $table->enum('status', [
                'pending_review', 'under_investigation',
                'scheduled_for_mediation', 'scheduled_for_hearing',
                'resolved', 'dismissed', 'escalated',
            ])->default('pending_review')->index();

            $table->foreignId('assigned_officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('complaint_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedInteger('size_bytes');
            $table->foreignId('uploaded_by_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_evidence');
        Schema::dropIfExists('complaints');
    }
};
