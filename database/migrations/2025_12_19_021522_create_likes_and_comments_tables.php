<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table Likes
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('karya_id')->constrained('karyas')->onDelete('cascade');
            $table->timestamps();
            
            // Prevent duplicate likes
            $table->unique(['user_id', 'karya_id']);
        });

        // Table Comments
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('karya_id')->constrained('karyas')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });

        // Update Notifications table
        Schema::table('notifications', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('type');
            }
            if (!Schema::hasColumn('notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('likes');
        
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['type', 'data', 'read_at']);
        });
    }
};