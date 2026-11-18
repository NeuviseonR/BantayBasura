<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            // Nullable at the database level so existing rows (and admin accounts
            // created by hand) don't break. The sign-up form requires them.
            $table->string('first_name', 60)->nullable()->after('name');
            $table->string('middle_name', 60)->nullable()->after('first_name');
            $table->string('last_name', 60)->nullable()->after('middle_name');
            $table->date('birthdate')->nullable()->after('last_name');
            $table->string('sex', 20)->nullable()->after('birthdate');
            $table->string('mobile', 20)->nullable()->unique()->after('email');
            $table->foreignId('barangay_id')->nullable()->after('mobile')
                  ->constrained('barangays')->nullOnDelete();
            $table->string('zone', 20)->nullable()->after('barangay_id');
            $table->string('address', 150)->nullable()->after('zone');
            // resident | official | admin. Sign-up always creates 'resident'.
            $table->string('role', 20)->default('resident')->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['barangay_id']);
            $table->dropUnique(['mobile']);
            $table->dropColumn([
                'first_name', 'middle_name', 'last_name', 'birthdate', 'sex',
                'mobile', 'barangay_id', 'zone', 'address', 'role',
            ]);
        });

        Schema::dropIfExists('barangays');
    }
};
