<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // WAJIB: dropColumn butuh doctrine/dbal di beberapa versi Laravel/MySQL.
        // Tapi untuk drop 1 kolom biasanya aman. Kalau error soal dbal, bilang ya.

        if (Schema::hasColumn('karyas', 'isi')) {
            Schema::table('karyas', function (Blueprint $table) {
                $table->dropColumn('isi');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('karyas', 'isi')) {
            Schema::table('karyas', function (Blueprint $table) {
                $table->longText('isi')->nullable();
            });
        }
    }
};
