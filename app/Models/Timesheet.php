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

    public static function getLogsForEmployeeOnDate(int|string $employeeId, string $date): array
    {
        return self::forLogsBetweenDates($employeeId, $date, $date)
            ->get(['time_in', 'time_out'])
            ->toArray();
    }


    public function scopeForLogsBetweenDates($query, $employeeId, $fromDate, $toDate)
    {
        return $query->where('employee_id', $employeeId)
                     ->whereBetween('work_date', [$fromDate, $toDate])
                     ->orderBy('work_date');

    }
}
