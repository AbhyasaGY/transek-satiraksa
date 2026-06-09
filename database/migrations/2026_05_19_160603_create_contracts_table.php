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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            // Kolom user_id sebagai Foreign Key ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('contract_number');
            $table->string('file_path');
            $table->string('status')->default('Pending');
            $table->date('signed_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};