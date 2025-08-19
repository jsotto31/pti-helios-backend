<?php

namespace App\Http\Requests\OnlineApplication;

use Illuminate\Foundation\Http\FormRequest;

class LeaveApplicationRequest extends FormRequest
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
            'type'           => ['required', 'string', 'max:255'],
            'date_from'      => ['required', 'date', 'before_or_equal:date_to'],
            'date_to'        => ['required', 'date', 'after_or_equal:date_from'],
            'number_of_days' => ['required', 'integer', 'min:1'],
            'reason'         => ['nullable', 'string', 'max:1000'],
            'allow_approver' => ['boolean'],
            'with_pay'       => ['boolean'],
            'created_by'     => ['required', 'string', 'exists:users,employee_id']
        ];
    }

    public function prepareForValidation() : void{
        $this->merge([
            'created_by' => $this->user()->employee_id,
        ]);
    }
}
