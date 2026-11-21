<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('volunteer_points')->default(0);
        });
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); $table->string('id_type'); $table->string('id_number');
            $table->string('id_front_path'); $table->string('id_back_path')->nullable();
            $table->string('guardian_name')->nullable(); $table->string('guardian_phone')->nullable();
            $table->string('organization_name')->nullable(); $table->string('organization_type')->nullable(); $table->string('organization_role')->nullable();
            $table->json('skills')->nullable(); $table->json('availability')->nullable();
            $table->string('emergency_contact_name'); $table->string('emergency_contact_phone'); $table->string('emergency_contact_relationship');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamp('reviewed_at')->nullable(); $table->text('review_notes')->nullable(); $table->timestamps();
            $table->index(['user_id','status']);
        });
        Schema::create('cleanup_drives', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->text('description'); $table->string('location');
            $table->dateTime('start_at'); $table->dateTime('end_at'); $table->unsignedInteger('slots')->default(30);
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete(); $table->foreignId('barangay_id')->nullable()->constrained('barangays')->nullOnDelete(); $table->string('attendance_code_hash');
            $table->string('status')->default('scheduled'); $table->timestamps();
        });
        Schema::create('cleanup_drive_registrations', function (Blueprint $table) {
            $table->id(); $table->foreignId('cleanup_drive_id')->constrained()->cascadeOnDelete(); $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('registered_at')->nullable(); $table->string('attendance_status')->default('registered'); $table->string('verification_method')->nullable();
            $table->timestamp('verified_at')->nullable(); $table->timestamp('evidence_submitted_at')->nullable(); $table->timestamp('penalty_processed_at')->nullable();
            $table->unsignedInteger('points_awarded')->default(0); $table->unsignedInteger('points_deducted')->default(0); $table->timestamps();
            $table->unique(['cleanup_drive_id','volunteer_id']);
        });
        Schema::create('cleanup_drive_evidence', function (Blueprint $table) {
            $table->id(); $table->foreignId('registration_id')->constrained('cleanup_drive_registrations')->cascadeOnDelete();
            $table->string('photo_path'); $table->timestamp('uploaded_at'); $table->timestamps();
        });
        Schema::create('volunteer_point_transactions', function (Blueprint $table) {
            $table->id(); $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('registration_id')->nullable()->constrained('cleanup_drive_registrations')->nullOnDelete();
            $table->integer('points'); $table->string('type'); $table->text('description'); $table->integer('balance_after'); $table->timestamps();
        });
        Schema::create('volunteer_violations', function (Blueprint $table) {
            $table->id(); $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('cleanup_drive_registrations')->cascadeOnDelete();
            $table->string('type'); $table->text('description'); $table->timestamp('recorded_at'); $table->integer('points_deducted')->default(10); $table->string('status')->default('recorded'); $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('volunteer_violations'); Schema::dropIfExists('volunteer_point_transactions');
        Schema::dropIfExists('cleanup_drive_evidence'); Schema::dropIfExists('cleanup_drive_registrations'); Schema::dropIfExists('cleanup_drives'); Schema::dropIfExists('volunteer_applications');
        Schema::table('users', fn(Blueprint $table) => $table->dropColumn('volunteer_points'));
    }
};
