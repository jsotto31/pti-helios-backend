<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'employee_id', 'work_date', 'time_in', 'time_out'
    ];

    protected $casts = [
        'time_in' => 'date:H:i',
        'time_out' => 'date:H:i',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];
}
