<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortLinkResource extends JsonResource
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
            'is_active' => $this->is_active ? 'active' : 'inactive',
            'original_link' => $this->original_url,
            'short_code' => $this->short_code,
            'title' => $this->title,
            'custom_alias' => $this->custom_alias,
            'clicks_count' => $this->clicks_count ?? 0,
            'visits' => $this->whenLoaded('clicks', ClickResource::collection($this->clicks)),
        ];
    }
}
