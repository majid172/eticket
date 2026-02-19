<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Must be authenticated and not already have a company
        return $this->user() && !$this->user()->company;
    }

    public function rules(): array
    {
        return [
            'company_name'  => ['required', 'string', 'max:150'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'trade_license' => ['required', 'string', 'max:100'],
            'address'       => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required'  => 'Company name is required.',
            'company_name.max'       => 'Company name may not exceed 150 characters.',
            'contact_phone.required' => 'A contact phone number is required.',
            'contact_phone.max'      => 'Phone number may not exceed 20 characters.',
            'contact_email.email'    => 'Please enter a valid email address.',
            'trade_license.max'      => 'Trade license number may not exceed 100 characters.',
            'address.max'            => 'Address may not exceed 500 characters.',
        ];
    }

    protected function failedAuthorization(): never
    {
        abort(409, 'Company already exists. Use PUT to update your profile.');
    }
}
