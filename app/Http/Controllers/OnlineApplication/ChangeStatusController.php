<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineApplication\ChangeStatusRequest;
use Illuminate\Http\Request;

class ChangeStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ChangeStatusRequest $request)
    {
        $list_item = $request->approver_list_items;
        $currentApproverIndex = collect($request->approver_list_items)->search(fn($item) => $item['can_approve']);
        $currentApprover = $list_item[$currentApproverIndex];

        if($request->user()->type == 'admin'){
            $application->status = 'approved';
        }

        if($currentApprover['employee_id'] != $request->user()->employee_id){
            return response()->json([
                    'message' => 'You are not authorized to approve/disapprove the application from <b>' . $currentApprover['employee']['name'] . '</b>.',
                    'errors' => [],
            ], 422);
        }

        $list_item[$currentApproverIndex]['can_approve'] = 0;

        $application = $currentApprover['application_type']::find($currentApprover['application_id']);
        
        if($currentApprover['status'] == 'approved' && $currentApprover['last_approver']){
            $application->status = 'approved';
            $application->save();
        }else if($currentApprover['status'] == 'disapproved'){
            $application->status = 'disapproved';
            $application->save();
        }else if ($currentApprover['status'] == 'pending'){
            return response()->json([
                    'message' => 'The selected status is invalid, please try again.',
                    'errors' => [
                        'status' => ['The request is still pending approval.'],
                    ],
            ], 422);
        }else{
            $list_item[$currentApproverIndex + 1]['can_approve'] = 1;
        }

        $application->approval_sequence_items()->delete();
        $application->approval_sequence_items()->createMany($list_item);

        return response(null, 200);
    }
}
