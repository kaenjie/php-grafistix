<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderResource;

class PackageResource extends JsonResource
{
    /**
     * toArray
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name, // Nama paket
            'description' => $this->description, // Deskripsi paket
            'price'       => $this->price, // Harga paket
            'duration'    => $this->duration, // Durasi paket (opsional)
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

    public function show(Order $order)
    {
        return new OrderResource(true, 'Order retrieved successfully!', $order->load('package'));
    }

}
