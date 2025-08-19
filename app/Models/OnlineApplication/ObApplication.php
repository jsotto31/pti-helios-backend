<?php

namespace App\Models\OnlineApplication;

use App\Queries\ObApplicationQuery;
use Illuminate\Database\Eloquent\Model;

class ObApplication extends Model
{
    use ObApplicationQuery;


    protected $fillable = [
        "employee_id",     // foreign key → employees table
        "created_by",      // foreign key → users table
        "date_from",       // ISO date (start)
        "date_to",         // ISO date (end)
        "time_from",       
        "time_to",         
        "type",            // string (e.g. "sick leave")
        "reason",          // text
        "allow_approver",  // boolean
        "status",          // string (e.g. pending, approved, rejected)
    ];

    protected $casts = [
        'date_from' => 'date:Y-m-d',
        'date_to'   => 'date:Y-m-d',
        'time_from'   => 'date:H:i',
        'time_to'   => 'date:H:i',
        'created_at' => 'date:Y-m-d',
        'updated_at'   => 'date:Y-m-d',
        'allow_approver' => 'boolean',
    ];

    public static function getFilteredData($request)
    {
        return self::fetch($request);
    }
}
