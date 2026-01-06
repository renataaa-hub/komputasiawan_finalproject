<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kalau sudah ada, jangan tambah lagi (biar migrate gak berhenti)
        if (!Schema::hasColumn('karyas', 'slug')) {
            Schema::table('karyas', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('judul');
            });
        }
    }

    public function down(): void
    {
        // Kalau ada, baru drop (biar aman juga)
        if (Schema::hasColumn('karyas', 'slug')) {
            Schema::table('karyas', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
