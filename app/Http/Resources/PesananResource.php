<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ProdukPesanan;
use App\Models\DetailPesanan;
use App\Http\Resources\ProdukPesananResource;
use App\Http\Resources\DetailPesananResource;

class PesananResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    
    public function toArray($request)
    {
        $data = DetailPesanan::orderBy('id', 'ASC')->where('id_pesanan','=', $this->id)->get();
        return [
            'id' => $this->id,
            'id_user' => $this->id_user,
            'waktu_pesan' => $this->waktu_pesan,
            'waktu_proses' => $this->waktu_proses,
            'waktu_kirim' => $this->waktu_kirim,
            'waktu_selesai' => $this->waktu_selesai,
            'total_harga' => $this->total_harga,
            'ongkir' => $this->total_harga,
            'total_produk' => $this->total_produk,
            'alamat_antar' => $this->alamat_antar,
            'latitude_antar' => $this->latitude_antar,
            'longitude_antar' => $this->longitude_antar,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'produk' => DetailPesananResource::collection($data),
        ];
    }
}

