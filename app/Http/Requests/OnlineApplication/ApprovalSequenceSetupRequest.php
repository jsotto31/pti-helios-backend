<?php

namespace App\Http\Requests\OnlineApplication;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalSequenceSetupRequest extends FormRequest
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
            'type' => ['required'],
            'employee_list' => ['required', 'array'],
            'employee_list.*' => ['array'],
            'employee_list.*.*.employee_id' => ['required', 'string'],
            'employee_list.*.*.approver_id' => ['nullable', function ($attribute, $value, $fail) {
                $keys = explode('.', $attribute); 
                $employeeKey = $keys[1]; 
                $employeeId = $this->input("employee_list.$employeeKey.{$keys[2]}.employee_id");

                if ($value === $employeeId) {
                    $fail("The approver_id cannot be the same as employee.");
                }
            },], 
        ];
    }
}