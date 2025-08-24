<?php

namespace App\Models\OnlineApplication;

use Illuminate\Database\Eloquent\Model;

class ChangeScheduleApplicationItem extends Model
{
    protected $fillable = [
        'change_schedule_application_id',
        'start',
        'end',
        'day',
        'tardy_start',
        'absent_start',
        'early_dismiss',
        'date_effective',
    ];

    protected $casts = [
        'start'   => 'date:H:i',
        'end'   => 'date:H:i',
        'tardy_start'   => 'date:H:i',
        'absent_start'   => 'date:H:i',
        'early_dismiss'   => 'date:H:i',
        'date_effective'   => 'date:H:i',
    ];
}
