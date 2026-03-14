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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek')->default('Program Makan Bergizi Gratis');
            $table->string('sumber_dana')->nullable();
            $table->decimal('modal_awal', 15, 2); // Rp 1.000.000.000
            $table->decimal('saldo_saat_ini', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
