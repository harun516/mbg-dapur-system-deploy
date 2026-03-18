<?php

namespace App\Models\Anggaran;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (Opsional jika nama tabel jamak/plural).
     */
    protected $table = 'budget_requests';

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment)
     * Ini akan memperbaiki error MassAssignmentException kamu tadi.
     */
    protected $fillable = [
        'perihal',
        'nominal',
        'user_id',
        'status',
        'is_enable',
        'catatan',
    ];

    /**
     * Cast atribut ke tipe data tertentu.
     * Kita pastikan is_enable jadi boolean dan nominal jadi float/decimal.
     */
    protected $casts = [
        'is_enable' => 'boolean',
        'nominal' => 'decimal:2',
    ];

    /**
     * Relasi: Satu permintaan budget (BudgetRequest) dimiliki oleh satu User.
     * Jadi nanti kamu bisa panggil: $request->user->name.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper untuk cek status dengan cepat (Opsional).
     */
    public function isApproved()
    {
        return 'approved' === $this->status;
    }

    public function isPending()
    {
        return 'pending' === $this->status;
    }
}
