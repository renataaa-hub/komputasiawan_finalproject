<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('jenis')->nullable();
            $table->text('deskripsi')->nullable();
            $table->longText('konten');
            $table->string('kategori')->nullable();
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->boolean('is_draft')->default(true);
            $table->enum('akses', ['publik', 'pribadi'])->default('publik');
            $table->string('cover')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('status_monetisasi', ['active', 'inactive'])->default('inactive');
            $table->integer('harga')->nullable();
            $table->integer('pendapatan')->default(0);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyas');
    }
};