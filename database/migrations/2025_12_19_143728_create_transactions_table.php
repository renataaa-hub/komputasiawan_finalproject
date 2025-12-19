<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->integer('gross_amount');
            $table->string('payment_type')->nullable();
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel', 'deny'])->default('pending');
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};