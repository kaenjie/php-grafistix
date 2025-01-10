<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PackageCategoryResource;

class OptionPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'package_id' => $this->package_id,
            'category' => new PackageCategoryResource($this->whenLoaded('category')), // Pastikan resource untuk kategori ada
        ];
    }
}
