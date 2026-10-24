<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ExplainRiskRequest extends FormRequest
{
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'allocation' => ['required', 'array:equities,bonds,cash,property,alternatives'],
            'allocation.*' => ['required', 'numeric', 'between:0,100'],
            'years' => ['required', 'integer', 'between:1,50'],
            'tolerance' => ['required', 'integer', 'in:5,10,15,20,30,40,50'],
            'consent' => ['accepted'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $allocation = $this->input('allocation');

                if (is_array($allocation) && count($allocation) === 5 && abs(array_sum(array_map('floatval', $allocation)) - 100) > 0.5) {
                    $validator->errors()->add('allocation', 'The allocation must add up to 100%.');
                }
            },
        ];
    }
}
