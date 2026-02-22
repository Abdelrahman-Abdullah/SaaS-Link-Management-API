<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
{
    use ApiResponseHelper;
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
            'verify_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'same:password',
        ];
    }

    public function failedValidation(Validator $validator)
    {
           $response = $this->apiResponse(
                message: $validator->errors()->toArray() ?? 'Validation failed',
                status: 422,
            );

            throw new HttpResponseException($response);
    }

}
