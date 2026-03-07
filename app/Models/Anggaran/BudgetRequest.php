<?php

namespace App\Models\Anggaran;

use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    public function up()
{
    Schema::create('budget_requests', function (Blueprint $table) {
        $table->id();
        $table->string('perihal');
        $table->decimal('nominal', 15, 2);
        $table->text('alasan')->nullable();
        $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
        $table->text('catatan_admin')->nullable();
        $table->timestamps();
    });
}
}
