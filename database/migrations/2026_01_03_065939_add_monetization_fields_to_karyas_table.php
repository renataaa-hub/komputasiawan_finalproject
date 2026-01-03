<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('karyas', function (Blueprint $table) {
        if (!Schema::hasColumn('karyas', 'status_monetisasi')) {
            $table->string('status_monetisasi')->default('inactive')->after('status');
        }
        if (!Schema::hasColumn('karyas', 'harga')) {
            $table->unsignedInteger('harga')->default(0)->after('status_monetisasi');
        }
        if (!Schema::hasColumn('karyas', 'pendapatan')) {
            $table->unsignedBigInteger('pendapatan')->default(0)->after('harga');
        }
    });
}


    public function down(): void
{
    Schema::table('karyas', function (Blueprint $table) {
        if (Schema::hasColumn('karyas', 'pendapatan')) $table->dropColumn('pendapatan');
        if (Schema::hasColumn('karyas', 'harga')) $table->dropColumn('harga');
        if (Schema::hasColumn('karyas', 'status_monetisasi')) $table->dropColumn('status_monetisasi');
    });
}

};
