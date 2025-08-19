<?php

namespace App\Http\Requests\OnlineApplication;

use Illuminate\Foundation\Http\FormRequest;

class CorrectionApplicationRequest extends FormRequest
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
            'date'           => ['required', 'date'],
            'reason'         => ['nullable', 'string'],
            'allow_approver' => ['required', 'boolean'],

            'items'                  => ['required', 'array', 'min:1'],
            'items.*.actual_time_in'  => ['nullable', 'date_format:H:i'],
            'items.*.actual_time_out' => ['nullable', 'date_format:H:i'],
            'items.*.request_time_in' => ['nullable', 'date_format:H:i'],
            'items.*.request_time_out'=> ['nullable', 'date_format:H:i'],
            'items.*.status'         => ['sometimes', 'in:NEW,APPROVED,REJECTED'],
        ];
    }

    public function prepareForValidation() : void{
        $this->merge([
            'created_by' => $this->user()->employee_id,
            'date' => date("Y-m-d", strtotime($this->date)),
        ]);
    }
}
