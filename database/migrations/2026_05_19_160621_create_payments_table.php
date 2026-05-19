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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
        $table->enum('payment_method', ['Uang Tunai', 'Kartu Debit', 'Kartu Kredit', 'Dana', 'Gopay', 'Ovo']);
        $table->decimal('amount_paid', 15, 2);
        $table->decimal('change_amount', 15, 2)->default(0);
        $table->enum('payment_status', ['Unpaid', 'Paid', 'Refunded'])->default('Paid');
        $table->text('gateway_response')->nullable(); // Log API Payment Gateway (Syarat Secure by Design)
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};