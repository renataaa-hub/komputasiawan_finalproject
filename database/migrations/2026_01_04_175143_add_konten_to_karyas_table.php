<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            if (!Schema::hasColumn('karyas', 'konten')) {
                $table->longText('konten')->nullable()->after('deskripsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            if (Schema::hasColumn('karyas', 'konten')) {
                $table->dropColumn('konten');
            }
        });
    }
};
