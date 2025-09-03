<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineApplication\LeaveApplicationRequest;
use App\Models\OnlineApplication\LeaveApplication;
use Illuminate\Http\Request;

class LeaveApplicationController extends Controller
{
    public function index(Request $request){
        return LeaveApplication::getFilteredData($request);
    }

    public function store(LeaveApplicationRequest $request){

        if(!LeaveApplication::hasApproverSetup($request->employee_id, 'leave_application')){
            return response()->json([
                'message' => 'No approval sequence setup found for this application.',
                'errors' => []
            ], 422);
        }

        $leave_application = LeaveApplication::create([
            ...$request->only(["employee_id",
                "type",
                "number_of_days",
                "reason",
                "allow_approver",
                "with_pay",
                "created_by"
            ]), 
            'date_from' => date('Y-m-d', strtotime($request->date_from)), 
            'date_to' => date('Y-m-d', strtotime($request->date_to))
        ]);

        $approver_sequence_items = $leave_application->createApproverSequence($request->employee_id, 'leave_application');

        $leave_application = $leave_application->toArray();
        $leave_application['approver_sequence_items'] = $approver_sequence_items;

        return response($leave_application, 201);
    }

    public function cancel(Request $requst, LeaveApplication $leaveApplication){
        $leaveApplication->cancel();
    }

    public function update(LeaveApplication $leaveApplication, LeaveApplicationRequest $request){
        $leaveApplication->update([
            ...$request->only(["employee_id",
                "type",
                "number_of_days",
                "reason",
                "allow_approver",
                "with_pay",
                "created_by"
            ]), 
            'date_from' => date('Y-m-d', strtotime($request->date_from)), 
            'date_to' => date('Y-m-d', strtotime($request->date_to))
        ]);

        return response($leaveApplication, 201);
    }

    public function destroy(LeaveApplication $leaveApplication){
        $leaveApplication->delete();

        return response(null, 204);
    }
}
