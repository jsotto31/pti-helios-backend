<?php

namespace App\Models\OnlineApplication;

use App\Queries\OvertimeApplicationQuery;
use App\Traits\Approvable;
use Illuminate\Database\Eloquent\Model;

class OvertimeApplication extends Model
{
    use OvertimeApplicationQuery, Approvable;

    protected $fillable = [
        "employee_id",     // foreign key → employees table
        "created_by",      // foreign key → users table
        "date",            // ISO date
        "time_from",       
        "time_to",         
        "duration",        
        "reason",          // text
        "allow_approver",  // boolean
        "status",          // string (e.g. pending, approved, rejected)
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'time_from'   => 'date:H:i',
        'time_to'   => 'date:H:i',
        'duration'   => 'date:H:i',
        'created_at' => 'date:Y-m-d',
        'updated_at'   => 'date:Y-m-d',
        'allow_approver' => 'boolean',
    ];

    public static function getFilteredData($request)
    {
        return self::fetch($request);
    }
}
