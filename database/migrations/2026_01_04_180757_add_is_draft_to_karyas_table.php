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
            if (!Schema::hasColumn('karyas', 'is_draft')) {
                $table->boolean('is_draft')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            if (Schema::hasColumn('karyas', 'is_draft')) {
                $table->dropColumn('is_draft');
            }
        });
    }
};
