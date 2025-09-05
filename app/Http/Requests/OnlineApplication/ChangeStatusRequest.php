<?php

namespace App\Http\Requests\OnlineApplication;

use Illuminate\Foundation\Http\FormRequest;

class ChangeStatusRequest extends FormRequest
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
            'approver_list_items' => 'required|array',
            'approver_list_items.*.id' => 'required|integer',
            'approver_list_items.*.application_type' => 'required|string',
            'approver_list_items.*.application_id' => 'required|integer',
            'approver_list_items.*.employee_id' => 'required|string',
            'approver_list_items.*.status' => 'required|string|in:approved,pending,disapproved',
            'approver_list_items.*.can_approve' => 'required|boolean',
            'approver_list_items.*.last_approver' => 'required|boolean',
            'approver_list_items.*.selected' => 'nullable',
            'approver_list_items.*.employee' => 'array',
            'approver_list_items.*.employee.id' => 'required|integer',
            'approver_list_items.*.employee.name' => 'required|string',
            'approver_list_items.*.approver' => 'array',
            'approver_list_items.*.approver.id' => 'required|integer',
            'approver_list_items.*.approver.name' => 'required|string',
        ];
    }
}
