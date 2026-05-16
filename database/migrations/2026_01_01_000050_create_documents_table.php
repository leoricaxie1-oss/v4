<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');                     // key from config('panipone.documents')
            $table->string('purpose');
            $table->text('details')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->enum('status', [
                'pending_review', 'approved', 'rejected',
                'ready_for_pickup', 'released', 'cancelled',
            ])->default('pending_review')->index();

            $table->date('pickup_date')->nullable();
            $table->string('pickup_schedule')->nullable();       // e.g. "8:00 AM - 12:00 PM"
            $table->text('claim_requirements')->nullable();

            $table->foreignId('processed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
