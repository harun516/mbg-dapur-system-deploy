<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Skema tabel Penerima MBG
    public function up()
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->boolean('status_enable')->default(1);
            $table->string('nama_lembaga'); // Nama Sekolah/Yayasan/Kelompok
            $table->string('pimpinan')->nullable(); // Kepala Sekolah/Ketua
            $table->text('alamat');
            $table->string('no_hp_pic');
            $table->string('nama_pic'); // Nama Koordinator
            $table->integer('jumlah_porsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipients');
    }
};
