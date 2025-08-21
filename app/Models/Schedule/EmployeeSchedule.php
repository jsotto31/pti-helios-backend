<?php

namespace App\Models\Schedule;

use Database\Factories\EmployeeScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'start',
        'end',
        'day',
        'tardy_start',
        'absent_start',
        'early_dismiss',
        'date_effective',
    ];

    public static function newFactory()
    {
        return EmployeeScheduleFactory::new();
    }

    public function scopeForEmployee($query, $employeeId){
        return $query->where('employee_id', $employeeId);
    }

    public function scopeEffectiveOn($query, $date){
        if($date){
            return $query->whereDate('date_effective', $date);
        }

        return $query;
    }

    public static function createOrUpdateByUniqueKeys(array $data){
        return static::updateOrCreate(
            [
                'employee_id' => $data['employee_id'],
                'day' => $data['day'],
                'start' => $data['start'],
                'end' => $data['end'],
                'date_effective' => $data['date_effective']
            ],

            $data
        );
    }

}
