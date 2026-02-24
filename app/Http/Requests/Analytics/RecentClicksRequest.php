<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Exceptions\HttpResponseException;

class RecentClicksRequest extends FormRequest
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
            'limit' => 'sometimes|integer|min:1|max:50',
        ];
    }

    public function messages()
    {
        return [
            'limit.integer' => 'Limit must be a number.',
            'limit.min'     => 'Limit must be at least 1.',
            'limit.max'     => 'Limit cannot exceed 50.',
        ];
    }

    public function failedValidation($validator)
    {
        $response = $this->apiResponse(
            data: $validator->errors()->toArray(),
            message: 'Validation failed',
            code: 422
        );
        throw new HttpResponseException($response);
    }
}
