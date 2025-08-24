<?php

namespace App\Models\OnlineApplication;

use App\Queries\ApprovalSetupQuery;
use Illuminate\Database\Eloquent\Model;

class ApprovalSequenceItem extends Model
{
    use ApprovalSetupQuery;

    protected $guarded = ['id'];

    public static function getFilteredData($request)
    {
        return self::fetch($request);
    }
}
