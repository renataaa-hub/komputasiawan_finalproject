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
            // Kolom untuk status akses (publik/private/premium)
            // Kita beri default 'publik' agar data lama tidak error
            $table->string('akses')->default('publik')->after('is_draft');
        });
    }

    public function down()
    {
        Schema::table('karyas', function (Blueprint $table) {
            $table->dropColumn('akses');
        });
    }
};
