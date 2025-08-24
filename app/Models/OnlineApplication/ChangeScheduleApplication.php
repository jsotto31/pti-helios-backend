<?php

namespace App\Models\OnlineApplication;

use App\Queries\ChangeScheduleApplicationQuery;
use App\Traits\Approvable;
use Illuminate\Database\Eloquent\Model;

class ChangeScheduleApplication extends Model
{
    use ChangeScheduleApplicationQuery, Approvable;

    protected $fillable = [
        "employee_id",     // foreign key → employees table
        "created_by",      // foreign key → users table
        "date_from",       // ISO date (start)
        "date_to",         // ISO date (end)
        "date",            // ISO date (end)
        "type",            // string (e.g permanent, temporary)
        "reason",          // text
        "allow_approver",  // boolean
        "status",          // string (e.g. pending, approved, rejected)
    ];

    protected $casts = [
        'date_from' => 'date:Y-m-d',
        'date_to'   => 'date:Y-m-d',
        'date'   => 'date:Y-m-d',
        'created_at' => 'date:Y-m-d',
        'updated_at'   => 'date:Y-m-d',
        'allow_approver' => 'boolean',
    ];

    public function items(){
        return $this->hasMany(ChangeScheduleApplicationItem::class);
    }

    public static function getFilteredData($request){
        return self::fetch($request);
    }
}
