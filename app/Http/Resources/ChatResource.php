<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {        
    return [
        'id' => $this->id,
        'id_user' => $this->id_user,
        'id_pesanan' => $this->id_pesanan,
        'chat' => $this->chat,
        'waktu' => $this->waktu,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
    }
}
