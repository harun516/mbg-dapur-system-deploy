<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = ['no_request', 'tanggal_request', 'status', 'status_enable'];

    public function details()
    {
        return $this->hasMany(RequestDetail::class);
    }
}
