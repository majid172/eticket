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

    /**
     * Sanitize inputs before validation.
     * Converts empty string to null so the nullable|integer rule works correctly.
     */
    protected function prepareForValidation(): void
    {
        if ($this->distance_km === '' || $this->distance_km === null) {
            $this->merge(['distance_km' => null]);
        } else {
            $this->merge(['distance_km' => (int) $this->distance_km]);
        }
    }

    public function rules(): array
    {
        return [
            'source_city'      => ['required', 'string', 'max:255'],
            'destination_city' => ['required', 'string', 'max:255', 'different:source_city'],
            'distance_km'      => ['sometimes', 'nullable', 'integer', 'min:1'],
        ];
    }
}
