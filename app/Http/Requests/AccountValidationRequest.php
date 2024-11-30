<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @bodyParam account_number string required The phone number to validate. Example: 0700000000
 * @bodyParam institution_code string required The institution code (63902 for Safaricom, 63903 for Airtel, 63904 for Telkom). Example: 63902
 */
class AccountValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_number' => 'required|string',
            'institution_code' => 'required|string'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'account_number.required' => 'The phone number is required',
            'account_number.string' => 'The phone number must be a string',
            'institution_code.required' => 'The institution code is required',
            'institution_code.string' => 'The institution code must be a string'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'account_number' => 'phone number',
            'institution_code' => 'institution code'
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'response_code' => 412,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}
