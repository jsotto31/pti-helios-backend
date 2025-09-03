<?php

namespace App\Models\OnlineApplication;

use App\Queries\LeaveApplicationQuery;
use App\Traits\Approvable;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use LeaveApplicationQuery, Approvable;

    protected $fillable = [
        "employee_id",     // foreign key → employees table
        "created_by",      // foreign key → users table
        "date_from",       // ISO date (start)
        "date_to",         // ISO date (end)
        "number_of_days",  // int
        "type",            // string (e.g. "sick leave")
        "reason",          // text
        "allow_approver",  // boolean
        "with_pay",        // boolean
        "status",          // string (e.g. pending, approved, rejected)
    ];

    protected $casts = [
        'date_from' => 'date:Y-m-d',
        'date_to'   => 'date:Y-m-d',
        'created_at' => 'date:Y-m-d',
        'updated_at'   => 'date:Y-m-d',
        'allow_approver' => 'boolean',
        'with_pay'       => 'boolean',
    ];

    public $type = 'leave_application';

    public static function getFilteredData($request)
    {
        return self::fetch($request);
    }

    public function scopeForEmployeeHasLeave($query, $employeeId, $date)
    {
        return $query->where('employee_id', $employeeId)
                     ->where('status', 'approved')
                     ->whereDate('date_from', '<=', $date)
                     ->whereDate('date_to', '>=', $date);
    }
}
