<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('karyas', function (Blueprint $table) {
            // Kita pakai longText biar muat cerita yang sangan panjaaang
            // Taruh setelah kolom 'deskripsi'
            $table->longText('konten')->nullable()->after('deskripsi');
        });
    }

    public function down()
    {
        Schema::table('karyas', function (Blueprint $table) {
            $table->dropColumn('konten');
        });
    }
};
