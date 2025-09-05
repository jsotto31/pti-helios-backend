<?php

namespace App\Models\OnlineApplication;

use App\Queries\CorrectionApplicationQuery;
use App\Traits\Approvable;
use Illuminate\Database\Eloquent\Model;

class CorrectionApplication extends Model
{
    use CorrectionApplicationQuery, Approvable;
    
    protected $fillable = [
        "employee_id",     
        "created_by",      
        "date",         
        "type",            
        "reason",          
        "allow_approver",  
        "status",          
    ];

    protected $casts = [
        'date'           => 'date:Y-m-d',
        'created_at'     => 'date:Y-m-d',
        'updated_at'     => 'date:Y-m-d',
        'allow_approver' => 'boolean',
    ];

    /**
     * Relations
     */
    public function items()
    {
        return $this->hasMany(CorrectionApplicationItem::class);
    }

    /**
     * Wrapper for query-based fetching.
     */
    public static function getFilteredData($request)
    {
        return self::fetch($request);
    }

    /**
     * Fetch all correction items (flattened) for an employee on a given date.
     *
     * @param  int    $employeeId
     * @param  string $date
     * @return array
     */
    public static function getCorrectionsForEmployeeOnDate(string $employeeId, string $date, string $status): array
    {
        return self::with('items')
            ->forEmployee($employeeId)
            ->onDate($date)
            ->statusIs($status)
            ->get()
            ->flatMap->items
            ->map(fn($item) => $item->getAttributes())
            ->toArray();
    }

      /**
     * Scope: filter applications for a specific employee.
     */
    public function scopeForEmployee($query, string $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope: filter applications for a specific date.
     */
    public function scopeOnDate($query, string $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope: filter applications by status.
     */
    public function scopeStatusIs($query, string $status)
    {
        return $query->where('status', $status);
    }
}
