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
        Schema::create('menu_requirements', function (Blueprint $table) {
            $table->id();
            // Gunakan unsignedBigInteger secara eksplisit agar tipe data identik dengan tabel menus
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('item_id');

            $table->double('qty_per_porsi');
            $table->timestamps();

            // Foreign Key manual
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_requirements');
    }
};
