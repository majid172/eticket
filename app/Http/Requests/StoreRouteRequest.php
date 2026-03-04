<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
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
            'source_city' => ['required', 'string', 'max:255'],
            'destination_city' => ['required', 'string', 'max:255', 'different:source_city'],
            'distance_km' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
