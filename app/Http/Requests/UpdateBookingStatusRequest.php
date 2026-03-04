<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingStatusRequest extends FormRequest
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
            'booking_status' => ['nullable', 'in:pending,confirmed,cancelled'],
            'payment_status' => ['nullable', 'in:unpaid,paid,refunded'],
        ];
    }
}
