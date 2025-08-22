<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeScheduleRequest extends FormRequest
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
            'schedules'      => 'required|array',
            'schedules.*.day'            => 'required|string',
            'schedules.*.start'          => 'required|date_format:H:i:s',
            'schedules.*.end'            => 'required|date_format:H:i:s',
            'schedules.*.tardy_start'    => 'required|date_format:H:i:s',
            'schedules.*.absent_start'   => 'required|date_format:H:i:s',
            'schedules.*.early_dismiss'  => 'required|date_format:H:i:s',
            'schedules.*.date_effective' => 'required|date',
        ];
    }
}
