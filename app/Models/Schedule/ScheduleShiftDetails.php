<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleShiftDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_shift_id',
        'day',
        'start',
        'end',
        'tardy_start',
        'absent_start',
        'early_dismiss',
    ];

    public static function newFactory()
    {
        return \Database\Factories\ScheduleShiftDetailsFactory::new();
    }

    public static function createOrUpdateByUniqueKeys(array $data){
        return static::updateOrCreate(
            [
                'schedule_shift_id' => $data['schedule_shift_id'],
                'day' => $data['day'],
                'start' => $data['start'],
                'end' => $data['end'],
                'tardy_start' => $data['tardy_start'],
                'absent_start' => $data['absent_start'],
                'early_dismiss' => $data['early_dismiss']
            ],

            $data
        );
    }
}
