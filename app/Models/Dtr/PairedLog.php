<?php

namespace App\Models\Dtr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairedLog extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'date', 'time_in', 'time_out'];

    public function scopeForLogsBetweenDates($query, $employeeId, $fromDate, $toDate)
    {
        return $query->where('employee_id', $employeeId)
                     ->whereBetween('date', [$fromDate, $toDate])
                     ->orderBy('date');

    }
}
