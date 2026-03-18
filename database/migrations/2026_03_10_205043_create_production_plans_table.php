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
        Schema::create('production_plans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_rencana');
            $table->foreignId('menu_id')->constrained('menus');
            $table->integer('total_porsi_target');
            $table->enum('status', ['Draft', 'Terkirim ke Dapur', 'Selesai'])->default('Draft');
            $table->text('catatan_admin')->nullable();
            $table->boolean('status_enable')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_plans');
    }
};
