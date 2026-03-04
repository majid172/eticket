<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route_id'       => ['sometimes', 'exists:routes,id'],
            'bus_id'         => ['sometimes', 'exists:buses,id'],
            'travel_date'    => ['sometimes', 'date', 'after_or_equal:today'],
            'departure_time' => ['sometimes', 'date_format:H:i'],
            'arrival_time'   => ['sometimes', 'date_format:H:i', 'after:departure_time'],
            'status'         => ['nullable', 'in:scheduled,completed,cancelled'],
            'base_price'     => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
