<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Encryption\DecryptException;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->text('id_number')->change();
        });

        DB::table('volunteer_applications')->select(['id', 'id_number'])->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $value = (string) $row->id_number;
                try {
                    Crypt::decryptString($value);
                    continue;
                } catch (DecryptException $exception) {
                    DB::table('volunteer_applications')->where('id', $row->id)->update([
                        'id_number' => Crypt::encryptString($value),
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('volunteer_applications')->select(['id', 'id_number'])->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                try {
                    $plain = Crypt::decryptString((string) $row->id_number);
                    DB::table('volunteer_applications')->where('id', $row->id)->update(['id_number' => $plain]);
                } catch (DecryptException $exception) {
                    // Leave an unexpected legacy value unchanged rather than losing it.
                }
            }
        });

        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->string('id_number', 255)->change();
        });
    }
};
