<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClickResource extends JsonResource
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
            'user_agent' => $this->user_agent,
            'city' => $this->city,
            'country' => $this->country,
            'region' => $this->region,
            'device_type' => $this->device_type,
            'browser' => $this->browser,
            'platform' => $this->platform,
            'clicked_at' => $this->created_at->diffForHumans(),
        ];
    }
}
