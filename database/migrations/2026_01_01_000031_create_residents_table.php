<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('household_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('province_id')->constrained()->restrictOnDelete();
            $table->foreignId('city_id')->constrained()->restrictOnDelete();
            $table->foreignId('barangay_id')->constrained()->restrictOnDelete();
            $table->foreignId('purok_id')->constrained()->restrictOnDelete();

            $table->date('birthdate')->nullable();
            $table->enum('sex', ['male', 'female', 'other'])->nullable();
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated', 'divorced'])->nullable();
            $table->string('occupation')->nullable();
            $table->string('citizenship')->default('Filipino');
            $table->string('religion')->nullable();

            // Tags
            $table->boolean('is_senior_citizen')->default(false)->index();
            $table->boolean('is_pwd')->default(false)->index();
            $table->boolean('is_solo_parent')->default(false)->index();
            $table->boolean('is_voter')->default(false);
            $table->boolean('is_household_head')->default(false);

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('emergency_contact_relation')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['purok_id', 'is_senior_citizen']);
            $table->index(['purok_id', 'is_pwd']);
        });

        // FK from households.head_resident_id now that residents exists
        Schema::table('households', function (Blueprint $table) {
            $table->foreign('head_resident_id')->references('id')->on('residents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropForeign(['head_resident_id']);
        });
        Schema::dropIfExists('residents');
    }
};
