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
    Schema::create('deliveries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('production_plan_id')->constrained('production_plans')->onDelete('cascade');
        $table->foreignId('recipient_id')->constrained('recipients');
        $table->foreignId('courier_id')->nullable()->constrained('users');
        $table->enum('status', ['Menunggu Kurir', 'Proses Antar', 'Selesai', 'Gagal'])->default('Menunggu Kurir');
        $table->string('foto_bukti')->nullable(); // Untuk menyimpan nama file foto dari kurir
        $table->timestamp('waktu_antar')->nullable();
        $table->timestamp('waktu_sampai')->nullable();
        $table->boolean('status_enable')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
