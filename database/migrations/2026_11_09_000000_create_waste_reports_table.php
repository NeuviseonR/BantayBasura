<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_reports', function (Blueprint $table) {
            $table->id();

            // Who sent it, and which barangay it belongs to (copied from the user
            // at the time of sending, so officials can filter by barangay).
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barangay_id')->nullable()->constrained()->nullOnDelete();
            $table->string('zone', 20)->nullable();

            $table->string('title', 100);
            $table->string('category', 30);
            $table->text('description');

            // The image itself lives on disk. The database only keeps its path,
            // for example "reports/abc123.jpg".
            $table->string('photo_path');

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            // pending | verified | resolved | rejected
            $table->string('status', 20)->default('pending')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_reports');
    }
};
