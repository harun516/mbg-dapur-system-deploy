<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tabel untuk setting gaji per role
        Schema::create('salary_configs', function (Blueprint $table) {
            $table->id();
            $table->string('role_name')->unique();
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan', 15, 2)->default(0);
            $table->decimal('total_diterima', 15, 2)->default(0); // Hasil tambah pokok + tunjangan
            $table->boolean('status_enable')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel untuk riwayat pembayaran gaji (transaksi)
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('budgets');
            $table->foreignId('user_id')->constrained('users');
            $table->string('periode_bulan');
            $table->date('tanggal_bayar');
            $table->decimal('total_diterima', 15, 2);
            $table->boolean('status_enable')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('salary_payments');
        Schema::dropIfExists('salary_configs');
    }
};
