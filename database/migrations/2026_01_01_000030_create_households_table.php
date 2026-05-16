<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('household_code')->unique();
            $table->foreignId('purok_id')->constrained()->restrictOnDelete();
            $table->string('street_address')->nullable();
            $table->foreignId('head_resident_id')->nullable();
            $table->unsignedInteger('members_count')->default(0);
            $table->json('utilities')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
