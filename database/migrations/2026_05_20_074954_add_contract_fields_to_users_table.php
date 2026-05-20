<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom status: Belum Upload, Menunggu Validasi, Disetujui, Ditolak
            $table->string('contract_status')->default('Belum Upload');
            $table->string('contract_file')->nullable(); // Alamat file PDF
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['contract_status', 'contract_file']);
        });
    }
};