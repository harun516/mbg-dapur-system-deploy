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
    Schema::table('productions', function (Blueprint $table) {
        // Tambahkan kolom plan_id setelah kolom id
        $table->unsignedBigInteger('plan_id')->nullable()->after('id');
        
        // Opsional: Buat foreign key agar data konsisten
        $table->foreign('plan_id')->references('id')->on('production_plans')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('productions', function (Blueprint $table) {
        $table->dropForeign(['plan_id']);
        $table->dropColumn('plan_id');
    });
}
};
