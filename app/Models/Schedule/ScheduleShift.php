<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_name'
    ];

    public static function newFactory()
    {
        return \Database\Factories\ScheduleShiftFactory::new();
    }
    

    public static function createOrUpdateByUniqueKey(array $data){
        return static::updateOrCreate(
            [
                'id' => $data['id'] ?? null
            ],

            $data
        );
    }    

    public function scopeForScheduleName($query, $scheduleName){
        if($scheduleName){
            $query->where('schedule_name', 'LIKE', "%$scheduleName%");
        }

        return $query;
    }
}
