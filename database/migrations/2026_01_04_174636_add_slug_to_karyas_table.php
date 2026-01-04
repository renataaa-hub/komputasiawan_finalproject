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
            // Tambahkan kolom slug setelah judul
            // Kita buat nullable dulu untuk menghindari error pada data lama
            $table->string('slug')->nullable()->after('judul'); 
        });
    }

    public function down()
    {
        Schema::table('karyas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
