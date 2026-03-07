<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('budget_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('budget_id')->constrained('budgets')->onDelete('cascade');
        $table->enum('tipe', ['masuk', 'keluar']); 
        $table->string('kategori'); // Modal Awal, Operasional, Gaji
        $table->string('sumber_dana')->nullable(); // Ditambahkan: Asal Dana
        $table->decimal('nominal', 15, 2);
        $table->string('keterangan')->nullable();
        $table->boolean('status_enable')->default(1); // Ditambahkan: 1=Tampil, 0=Sembunyi
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_transactions');
    }
};
