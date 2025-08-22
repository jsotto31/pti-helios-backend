<?php

namespace App\Models\Dtr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'log_date',
        'sched_start',
        'sched_end',
        'time_in',
        'time_out',
        'tardy',
        'tardy_seconds',
        'undertime',
        'undertime_seconds',
        'absent',
        'early_dismiss',
        'details',
    ];

    protected $attributes = [
        'details' => '{
            "on_leave": false,
            "on_ob": false,
            "has_ot": false,
            "has_correction": false,
            "has_missing_in": false,
            "has_missing_out": false
        }',
    ];

}
