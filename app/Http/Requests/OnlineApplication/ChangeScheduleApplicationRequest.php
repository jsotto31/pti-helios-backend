<?php

namespace App\Http\Requests\OnlineApplication;

use Illuminate\Foundation\Http\FormRequest;

class ChangeScheduleApplicationRequest extends FormRequest
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
            'employee_id'   => ['required', 'string'],
            'type'          => ['required', 'in:permanent,temporary'],

            'date'          => ['required_if:type,permanent', 'nullable', 'date'],
            'date_from'     => ['required_if:type,temporary', 'nullable', 'date'],
            'date_to'       => ['required_if:type,temporary', 'nullable', 'date', 'after_or_equal:date_from'],

            'reason'        => ['nullable', 'string'],
            'allow_approver'=> ['boolean'],
            'created_by'    => ['required', 'string'],

            'items'                         => ['required', 'array'],
            'items.*.start'                 => ['nullable', 'date_format:H:i'],
            'items.*.end'                   => ['nullable', 'date_format:H:i'],
            'items.*.day'                   => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'items.*.tardy_start'           => ['nullable', 'date_format:H:i'],
            'items.*.absent_start'          => ['nullable', 'date_format:H:i'],
            'items.*.early_dismiss'         => ['nullable', 'date_format:H:i'],
            'items.*.date_effective'        => ['nullable', 'date'],
        ];
    }

    public function prepareForValidation() : void{
        $items = [];

        if ($this->has('schedule')) {
            foreach ($this->input('schedule', []) as $day => $rows) {
                foreach ($rows as $row) {
                    $items[] = [
                        'day'          => $day,
                        'start'        => $row['start'] ?? null,
                        'end'          => $row['end'] ?? null,
                        'tardy_start'  => $row['tardy_start'] ?? null,
                        'absent_start' => $row['absent_start'] ?? null,
                        'early_dismiss'=> $row['early_dismiss'] ?? null,
                        'date_effective' => $this->type == 'permanent' ? date('Y-m-d', strtotime($this->date)) : date("Y-m-d", strtotime($this->date_to)),
                    ];
                }
            }
        }

        $this->merge([
            'created_by' => $this->user()->employee_id,
            'date_from' => isset($this->date_from) && !empty($this->date_from) ? date('Y-m-d', strtotime($this->date_from)) : null,
            'date_to' => isset($this->date_to) && !empty($this->date_to) ? date('Y-m-d', strtotime($this->date_to)) : null,
            'date' => isset($this->date) && !empty($this->date) ? date('Y-m-d', strtotime($this->date)) : null,
            'items' => $items,
        ]);
    }
    public function messages(): array
    {
        return [
            'date.required_if'       => 'The date is required when type is permanent.',
            'date_from.required_if'  => 'The date from is required when type is temporary.',
            'date_to.required_if'    => 'The date to is required when type is temporary.',
            'items.*.day.required' => 'The day field is required for all schedule items.',
            'items.*.start.required' => 'The start time is required for all schedule items.',
            'items.*.end.required' => 'The end time is required for all schedule items.',
        ];
    }
}
