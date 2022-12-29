<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Produk;
use App\Http\Resources\ProdukResource;

class DetailPesananResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = Produk::orderBy('id', 'ASC')->where('id','=', $this->id_produk)->get();
        return [
            'id' => $this->id,
            'id_produk' => $this->id_produk,
            'id_pesanan' => $this->id_pesanan,
            'jumlah' => $this->jumlah,
            'sub_total' => $this->sub_total,
            'nama_produk' => $data[0]['nama_produk'],
            "id_user"=> $data[0]['id_user'],
            "id_kategori_produk"=> $data[0]['id_kategori_produk'],
            "nama_produk"=> $data[0]['nama_produk'],
            "harga"=> $data[0]['harga'],
            "stok"=> $data[0]['stok'],
            "deskripsi"=> $data[0]['deskripsi'],
            "foto"=> $data[0]['foto'],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
