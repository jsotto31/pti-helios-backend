<?php

namespace App\Http\Requests\OnlineApplication;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeRequest extends FormRequest
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
            'date'           => ['required', 'date'],
            'time_from'      => ['required', 'date_format:H:i'],
            'time_to'        => ['required', 'date_format:H:i', 'after_or_equal:time_from'],
            'duration'       => ['required', 'date_format:H:i'],
            'reason'         => ['nullable', 'string'],
            'allow_approver' => ['required', 'boolean'],
        ];
    }

    public function prepareForValidation() : void{
        $this->merge([
            'created_by' => $this->user()->employee_id,
            'date' => date("Y-m-d", strtotime($this->date)),
        ]);
    }
}
