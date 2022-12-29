<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function user()
    {
        // data model Produk dimiliki oleh model User melalui fk 'id_user'
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function pesanan()
    {
        return $this->belongsToMany('App\Models\Pesanan',
            'detail_pesanans',
            'id_produk',
            'id_pesanan');
    }
}
