<?php

namespace App\Http\Requests\UserInvestments\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponse;

class SaveUserInvestmentRequest extends FormRequest
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
            'name' => 'required|string|max:200',
            'email' => 'required|email|max:200',
            'country_code' => 'required|max:5',
            'contact_no' => 'required|max:15',
            'address' => 'required|string|max:1500',
            'dob' => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(18)->format('Y-m-d'),
            'investment_amount' => 'required|numeric|max:25000',
            'solution_ids' => 'required|array',
            'solution_ids.*' => 'exists:solutions,id',
            'account_id' => 'required',
            'access_token' => 'required'

        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'email.required' => 'Email is required',
    //         'email.email' => 'Please enter a valid email address',
    //         'email.max' => 'Length must be less than 200 characters',
    //         'password.required' => 'Password is required',
    //         'password.min' => 'Password must be at least 8 characters long',
    //         'device_token.required' => 'Device token is required',
    //         'device_id.required' => 'Device id is required',
    //         'push_platform_id.required' => 'Push notification platform is required'
    //     ];
    // }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation failed', $validator->errors(), 422));
    }
}
