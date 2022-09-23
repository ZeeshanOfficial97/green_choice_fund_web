<?php

namespace App\Http\Requests\AuthRequests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponse;

class AppUserUpdateRequest extends FormRequest
{
    use ApiResponse;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'nullable|max:200',
            'last_name' => 'nullable|max:200',
            'country_code' => 'nullable|max:10',
            'contact_no' => 'nullable|max:15',
            'privacy_policy_version' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.max' => 'Length must be less than 200 characters',
            'last_name.required' => 'Last name is required',
            'last_name.max' => 'Length must be less than 200 characters',
            'country_code.max' => 'Length must be less than 10 characters',
            'contact_no.max' => 'Length must be less than 15 characters',
            'privacy_policy_version.max' => 'Length must be less than 50 characters',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation failed', $validator->errors(), 422));
    }
}
