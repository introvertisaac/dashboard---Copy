<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class IdRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    protected function getValidatorInstance()
    {
        $rawContent = $this->getContent();

        if (preg_match('/"idnumber"\s*:\s*(\d+)/', $rawContent, $matches)) {
            $idnumber = $matches[1];

            $this->merge([
                'idnumber' => $idnumber
            ]);
            $this->request->set('idnumber', $idnumber);
        }

        return parent::getValidatorInstance();
    }

    public function validationData()
    {
        return parent::validationData();
    }

    public function rules(): array
    {
        return [
            'idnumber' => ['required', 'regex:/^[0-9]+$/']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'response_code' => 412,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    public function all($keys = null)
    {
        return parent::all($keys);
    }
}
