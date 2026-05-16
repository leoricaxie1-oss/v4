<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('business_name');
            $table->string('business_type');
            $table->text('description');
            $table->string('address');
            $table->foreignId('purok_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contact_email');
            $table->string('contact_phone', 20);
            $table->string('logo_path')->nullable();
            $table->string('permit_number')->nullable()->unique();
            $table->date('permit_issued_at')->nullable();
            $table->date('permit_expires_at')->nullable();
            $table->date('last_inspection_at')->nullable();
            $table->date('next_inspection_at')->nullable();

            // Approval workflow
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending')->index();
            $table->boolean('approved_by_secretary')->default(false);
            $table->boolean('approved_by_kagawad')->default(false);
            $table->boolean('approved_by_captain')->default(false);
            $table->foreignId('approved_by_secretary_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_kagawad_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_captain_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_secretary_at')->nullable();
            $table->timestamp('approved_by_kagawad_at')->nullable();
            $table->timestamp('approved_by_captain_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
