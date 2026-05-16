<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skill_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category');             // value from config('panipone.skill_categories')
            $table->string('custom_category')->nullable(); // when "Others"
            $table->string('title');
            $table->text('description');
            $table->decimal('rate', 10, 2)->nullable();
            $table->string('rate_unit')->nullable();   // per hour / per job
            $table->string('contact_email');
            $table->string('contact_phone', 20);
            $table->string('photo_path')->nullable();
            $table->json('tags')->nullable();

            // Approval (Secretary → Kagawad → Captain)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
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

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_services');
    }
};
