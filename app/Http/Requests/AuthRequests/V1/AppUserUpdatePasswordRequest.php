<?php

namespace App\Http\Requests\AuthRequests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponse;

class AppUserUpdatePasswordRequest extends FormRequest
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
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:new_password',

        ];
    }

    public function messages(): array
    {
        return [

            'old_password.required' => 'The old password is required',
            'old_password.min' => 'The old password must be at least 8 characters long',

            'new_password.required' => 'The new password is required',
            'new_password.min' => 'The new password must be at least 8 characters long',

        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation failed', $validator->errors(), 422));
    }
}
