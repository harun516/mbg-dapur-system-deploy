<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old CHECK constraint and create new one with all valid statuses
        if ('pgsql' === DB::connection()->getDriverName()) {
            // Drop old constraint
            DB::statement('ALTER TABLE production_plans DROP CONSTRAINT production_plans_status_check');

            // Add new constraint with new statuses
            DB::statement("ALTER TABLE production_plans ADD CONSTRAINT production_plans_status_check CHECK (status IN ('Draft', 'Terkirim ke Dapur', 'Selesai', 'Menunggu Dapur', 'Proses Masak', 'Packing', 'Siap Distribusi'))");
        } else {
            // Untuk MySQL, ganti enum
            Schema::table('production_plans', function (Blueprint $table) {
                $table->enum('status', [
                    'Draft',
                    'Terkirim ke Dapur',
                    'Selesai',
                    'Menunggu Dapur',
                    'Proses Masak',
                    'Packing',
                    'Siap Distribusi',
                ])->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ('pgsql' === DB::connection()->getDriverName()) {
            DB::statement('ALTER TABLE production_plans DROP CONSTRAINT production_plans_status_check');
            DB::statement("ALTER TABLE production_plans ADD CONSTRAINT production_plans_status_check CHECK (status IN ('Draft', 'Terkirim ke Dapur', 'Selesai'))");
        }
    }
};
