<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('karya_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karyas')->cascadeOnDelete();

            // kunci unik viewer: "u:123" kalau login, "ip:<hash>" kalau guest
            $table->string('viewer_key', 80);

            $table->timestamps();

            // ✅ ini yang bikin "1 orang = 1 view" (lifetime) per karya
            $table->unique(['karya_id', 'viewer_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_views');
    }
};
