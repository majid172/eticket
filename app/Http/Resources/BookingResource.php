<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'booking_reference' => $this->booking_reference,
            'user' => $this->whenLoaded('user'),
            'schedule_bus' => new ScheduleBusResource($this->whenLoaded('scheduleBus')),
            'total_amount' => $this->total_amount,
            'booking_status' => $this->booking_status,
            'payment_status' => $this->payment_status,
            'booked_at' => $this->booked_at,
            'created_at' => $this->created_at,
        ];
    }
}
