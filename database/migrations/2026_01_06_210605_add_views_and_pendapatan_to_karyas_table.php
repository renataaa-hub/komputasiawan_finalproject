<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            if (!Schema::hasColumn('karyas', 'views')) {
                $table->unsignedBigInteger('views')->default(0);
            }

            if (!Schema::hasColumn('karyas', 'pendapatan')) {
                $table->unsignedBigInteger('pendapatan')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            if (Schema::hasColumn('karyas', 'views')) {
                $table->dropColumn('views');
            }

            if (Schema::hasColumn('karyas', 'pendapatan')) {
                $table->dropColumn('pendapatan');
            }
        });
    }
};
