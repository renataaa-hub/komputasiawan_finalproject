<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            // kalau kolomnya sudah ada, jangan add lagi
            if (!Schema::hasColumn('karyas', 'akses')) {
                $table->string('akses')->default('publik')->after('is_draft');
            }
        });
    }

    public function down(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            if (Schema::hasColumn('karyas', 'akses')) {
                $table->dropColumn('akses');
            }
        });
    }
};
