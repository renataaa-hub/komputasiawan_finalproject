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
            // Kolom untuk menyimpan path gambar cover
            // Kita set nullable (boleh kosong) jika user tidak upload cover
            $table->string('cover')->nullable()->after('akses');
        });
    }

    public function down()
    {
        Schema::table('karyas', function (Blueprint $table) {
            $table->dropColumn('cover');
        });
    }
};
