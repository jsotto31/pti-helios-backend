<?php

namespace App\Models\OnlineApplication;

use App\Queries\ApprovalSetupQuery;
use Illuminate\Database\Eloquent\Model;

class ApprovalSequenceSetupItem extends Model
{
    use ApprovalSetupQuery;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'type', 'id'];

    public static function getFilteredData($request){
        return self::fetch($request);
    }
}
