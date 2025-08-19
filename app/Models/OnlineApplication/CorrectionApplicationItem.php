<?php

namespace App\Models\OnlineApplication;

use Illuminate\Database\Eloquent\Model;

class CorrectionApplicationItem extends Model
{
    protected $fillable = [
        "actual_time_in",
        "actual_time_out",
        "request_time_in",
        "request_time_out",
        "status",      
    ];

    protected $casts = [
        'actual_time_in'   => 'date:H:i',
        'actual_time_out'   => 'date:H:i',
        'request_time_in'   => 'date:H:i',
        'request_time_out'   => 'date:H:i',
    ];
}
