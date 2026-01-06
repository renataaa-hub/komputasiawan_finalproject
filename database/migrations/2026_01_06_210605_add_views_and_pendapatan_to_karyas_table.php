<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            $table->unsignedBigInteger('views_count')->default(0)->after('title'); // sesuaikan after
            $table->unsignedInteger('claimed_blocks')->default(0)->after('views_count');
            $table->boolean('monetization_active')->default(true)->after('claimed_blocks');
        });
    }

    public function down(): void
    {
        Schema::table('karyas', function (Blueprint $table) {
            $table->dropColumn(['views_count','claimed_blocks','monetization_active']);
        });
    }
};
