<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->unique();
            $table->string('password');
            $table->string('avatar_path')->nullable();

            // Multi-step approval (residents only)
            $table->enum('account_status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending')->index();
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

            // Account lockout
            $table->unsignedInteger('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
