<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class VehiclePlateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plate' => 'required',
        ];
    }


    public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator){

        throw new HttpResponseException(response()->json([
            'success'   => false,
            'response_code' => 412,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
