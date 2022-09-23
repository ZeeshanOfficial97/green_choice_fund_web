<?php

namespace App\Http\Requests\AuthRequests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponse;

class AppUserSocialLoginRequest extends FormRequest
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
            'access_token' => 'required',
            'provider' => 'required|max:10',
            'device_token' => 'required',
            'device_id' => 'required',
            'push_platform_id' => 'required'

        ];
    }

    public function messages(): array
    {
        return [
            'access_token.required' => 'Access token is required',
            'provider.required' => 'Provider is required',
            'provider.max' => 'Length must be less than 10 characters',
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
