<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleShiftDetailsRequest extends FormRequest
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
            'schedule_shift_id' => 'required|integer|exists:schedule_shifts,id',
            'schedules' => 'required|array',
            'schedules.*.day'            => 'required|string',
            'schedules.*.start'          => 'required|date_format:H:i:s',
            'schedules.*.end'            => 'required|date_format:H:i:s',
            'schedules.*.tardy_start'    => 'required|date_format:H:i:s',
            'schedules.*.absent_start'   => 'required|date_format:H:i:s',
            'schedules.*.early_dismiss'  => 'required|date_format:H:i:s'
        ];
    }
}
