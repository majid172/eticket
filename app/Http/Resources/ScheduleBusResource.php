<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleBusResource extends JsonResource
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
            'schedule_id' => $this->schedule_id,
            'bus_id' => $this->bus_id,
            'available_seats' => $this->available_seats,
            'status' => $this->status,
            'schedule' => $this->whenLoaded('schedule'),
            'bus' => $this->whenLoaded('bus'),
        ];
    }
}
