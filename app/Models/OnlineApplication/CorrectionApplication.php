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

    public function items(){
        return $this->hasMany(CorrectionApplicationItem::class);
    }

    public static function getFilteredData($request)
    {
        return self::fetch($request);
    }
}
