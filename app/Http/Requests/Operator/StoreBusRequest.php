<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gate is already handled by the 'role:operator' middleware
    }

    public function rules(): array
    {
        $busId = $this->route('id'); // null on store, set on update

        return [
            'bus_name'    => ['nullable', 'string', 'max:100'],
            'bus_number'  => ['required', 'string', 'max:50', 'unique:buses,bus_number,' . $busId],
            'bus_type'    => ['required', 'in:AC,Non-AC,Sleeper,Seater'],
            'total_seats' => ['required', 'integer', 'min:1', 'max:100'],
            'status'      => ['sometimes', 'in:active,inactive,maintenance'],
        ];
    }

    public function messages(): array
    {
        return [
            'bus_number.required' => 'Bus registration number is required.',
            'bus_number.unique'   => 'This bus number is already registered.',
            'bus_type.in'         => 'Bus type must be one of: AC, Non-AC, Sleeper, Seater.',
            'total_seats.min'     => 'A bus must have at least 1 seat.',
        ];
    }
}
