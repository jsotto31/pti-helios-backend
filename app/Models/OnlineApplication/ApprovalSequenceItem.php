<?php

namespace App\Models\OnlineApplication;

use App\Models\User;
use App\Queries\ApprovalSetupQuery;
use Illuminate\Database\Eloquent\Model;

class ApprovalSequenceItem extends Model
{
    use ApprovalSetupQuery;

    protected $guarded = ['id'];

    public function approver(){
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }

    public static function getFilteredData($request)
    {
        return self::fetch($request);
    }
}
