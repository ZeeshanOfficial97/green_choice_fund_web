<?php

namespace App\Http\Requests\AuthRequests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponse;

class AppUserSignupRequest extends FormRequest
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
            'name' => 'required|max:200',
            'email' => 'required|email|max:200|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8|same:password',
            'country_code' => 'nullable|max:10',
            'contact_no' => 'nullable|max:15',
            'device_token' => 'required',
            'device_id' => 'required',
            'push_platform_id' => 'required'

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'Length must be less than 200 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.max' => 'Length must be less than 200 characters',
            'email.unique' => 'This email is already associated with another user',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'country_code.max' => 'Length must be less than 10 characters',
            'contact_no.max' => 'Length must be less than 15 characters',
            'device_token.required' => 'Device token is required',
            'device_id.required' => 'Device id is required',
            'push_platform_id.required' => 'Push notification platform is required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation failed', $validator->errors(), 422));
    }
}
