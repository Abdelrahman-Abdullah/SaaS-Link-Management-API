<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponseHelper;
class ShortLikeGenerateRequest extends FormRequest
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
            'original_url' => 'required|url',
            'custom_alias' => 'nullable|string|alpha_dash|unique:links,custom_alias',
            'title' => 'nullable|string|max:255',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = $this->apiResponse(
            data: $validator->errors()->toArray(),
            message: 'Validation failed',
            status: 'error',
            code: 422
        );

        throw new HttpResponseException($response);
    }

}
