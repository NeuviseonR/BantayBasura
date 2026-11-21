<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('cleanup_drives', 'latitude')) {
            Schema::table('cleanup_drives', fn (Blueprint $table) => $table->decimal('latitude', 10, 7)->nullable());
        }
        if (!Schema::hasColumn('cleanup_drives', 'longitude')) {
            Schema::table('cleanup_drives', fn (Blueprint $table) => $table->decimal('longitude', 10, 7)->nullable());
        }
    }

    public function down(): void
    {
        $columns = [];
        if (Schema::hasColumn('cleanup_drives', 'latitude')) $columns[] = 'latitude';
        if (Schema::hasColumn('cleanup_drives', 'longitude')) $columns[] = 'longitude';
        if ($columns) Schema::table('cleanup_drives', fn (Blueprint $table) => $table->dropColumn($columns));
    }
};
