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
        Schema::table('budgets', function (Blueprint $table) {
            $table->decimal('saldo_belanja_gudang', 15, 2)->default(0)->after('saldo_saat_ini');
            if (!Schema::hasColumn('budgets', 'status_enable')) {
                $table->boolean('status_enable')->default(true)->after('saldo_belanja_gudang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['saldo_belanja_gudang', 'status_enable']);
        });
    }
};
