<?php

namespace App\Http\Requests\OnlineApplication;

use Illuminate\Foundation\Http\FormRequest;

class ObApplicationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id'    => ['required', 'string', 'exists:users,employee_id'],
            'date_from'      => ['required', 'date', 'before_or_equal:date_to'],
            'date_to'        => ['required', 'date', 'after_or_equal:date_from'],
            'time_from'      => ['required', 'date_format:H:i'],
            'time_to'        => ['required', 'date_format:H:i', 'after_or_equal:time_from'],
            'type'           => ['required', 'string', 'max:255'],
            'reason'         => ['nullable', 'string'],
            'allow_approver' => ['required', 'boolean'],
            'created_by'     => ['required', 'string', 'exists:users,employee_id']
        ];
    }

    public function prepareForValidation() : void{
        $this->merge([
            'created_by' => $this->user()->employee_id,
        ]);
    }
}
