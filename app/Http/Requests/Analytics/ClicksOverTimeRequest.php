<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClicksOverTimeRequest extends FormRequest
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
            'period' => 'sometimes|string|in:week,month,year',
            'from' => 'sometimes|date|before_or_equal:to',
            'to' => 'sometimes|date|after_or_equal:from',
        ];
    }

    public function messages()
    {
        return [
            'period.in' => 'The period must be one of the following: week, month, year.',
            'from.date' => 'The from field must be a valid date.',
            'to.date' => 'The to field must be a valid date.',
            'from.before_or_equal' => 'The from date must be before or equal to the to date.',
            'to.after_or_equal' => 'The to date must be after or equal to the from date.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = $this->apiResponse(
            data: $validator->errors()->toArray(),
            message: 'Validation failed',
            code: 422
        );
        throw new HttpResponseException($response);
    }
}
