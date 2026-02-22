<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponseHelper;
class VerifyResetCodeRequest extends FormRequest
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
            'email' => 'required|email',
            'code' => 'required|string|length:6',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->apiResponse(
            message: $validator->errors()->toArray() ?? 'Invalid input data.',
            status: 422,
        );
    }
}
