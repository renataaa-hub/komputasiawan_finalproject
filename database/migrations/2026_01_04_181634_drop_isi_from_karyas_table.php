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
            // Hapus kolom 'isi' karena sudah digantikan oleh 'konten'
            $table->dropColumn('isi');
        });
    }

    public function down()
    {
        Schema::table('karyas', function (Blueprint $table) {
            // (Opsional) Kembalikan kolom jika di-rollback
            $table->longText('isi')->nullable();
        });
    }
};
