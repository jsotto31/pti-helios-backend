<?php

namespace App\Models\Dtr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacialLog extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'time', 'site', 'device_id', 'processed'];

    public function scopeUnprocessedLogs($query)
    {
        return $query->select('employee_id')
                ->where('processed', 0)
                ->groupBy('employee_id')
                ->havingRaw('COUNT(*) >= 2')
                ->pluck('employee_id');
    }

    public function scopeForEmployeeUnprocessedLogs($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId)
                    ->where('processed', 0)
                    ->orderBy('time')
                    ->limit(2);
    }
}
