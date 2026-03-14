<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penerimaan_details', function (Blueprint $table) {
            $table->decimal('harga_satuan', 15, 2)->after('qty')->default(0);
            // Kita gunakan decimal untuk akurasi nilai uang
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan_details', function (Blueprint $table) {});
    }
};
