<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 100)->unique();
            $table->foreignId('user_id')->constrained('users'); // Relasi ke Kasir
            $table->string('customer_name')->nullable(); // Untuk pelanggan non-member
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Pending');
            $table->string('snap_token')->nullable(); // Untuk menyimpan snap token dari Midtrans
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
