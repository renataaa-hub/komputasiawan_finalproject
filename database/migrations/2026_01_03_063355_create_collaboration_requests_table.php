<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collaboration_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karya_id')->constrained('karyas')->cascadeOnDelete();

            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();

            $table->string('type')->default('request'); // request | invite
            $table->string('status')->default('pending'); // pending | accepted | rejected | canceled

            $table->text('message')->nullable();

            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['karya_id', 'from_user_id', 'to_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaboration_requests');
    }
};
