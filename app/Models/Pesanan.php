<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function produk()
    {
        return $this->belongsToMany('App\Models\Produk',
            'detail_pesanans',
            'id_pesanan',
            'id_produk',);
    }
}
