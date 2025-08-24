<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineApplication\ApprovalSequenceSetupRequest;
use App\Models\OnlineApplication\ApprovalSequenceSetupItem;
use App\Models\OnlineApplication\ApprovalSetup;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalSetupController extends Controller
{
    public function index(Request $request){
        return ApprovalSequenceSetupItem::getFilteredData($request);
    }

    public function store(ApprovalSequenceSetupRequest $request){
        foreach ($request->employee_list as $employee_id => $approval_sequence) {
            ApprovalSequenceSetupItem::where("employee_id", $employee_id)->delete();
            $approval_sequence = collect($approval_sequence)->filter(fn($item) => !!$item['approver_id'])
                                 ->map(fn($item) => array_merge($item, ['type' => $request->type]))->toArray();
            ApprovalSequenceSetupItem::insert($approval_sequence);
        }

        return response(null, 201);
    }
}
