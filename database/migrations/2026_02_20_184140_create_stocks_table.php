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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel items
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            // Mencatat No Batch agar sinkron dengan stok.blade.php kamu
            $table->string('no_batch')->nullable();
            $table->decimal('qty_masuk', 15, 4)->default(0);
            $table->decimal('qty_sisa', 15, 4)->default(0); // Ini yang akan dipotong produksi
            $table->date('expired_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
