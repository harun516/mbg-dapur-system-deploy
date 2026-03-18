<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $fillable = [
        'no_penerimaan',
        'tanggal',
        'supplier',
        'user_id',
    ];

    public function details()
    {
        return $this->hasMany(PenerimaanDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $last = self::latest()->first();

            if (!$last) {
                $number = 1;
            } else {
                $lastNumber = (int) substr($last->no_penerimaan, -3);
                $number = $lastNumber + 1;
            }

            $model->no_penerimaan = 'PNR-A'.str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}
