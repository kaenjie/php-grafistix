<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Debugging untuk melihat data yang ada
        // dd($this->file); // Uncomment jika ingin debug

        // Mengembalikan data banner dengan URL file gambar
        return [
            'id' => $this->id,
            'file' => $this->file,
            'image_url' => asset('storage/banners/' . $this->file),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
