<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineApplication\OvertimeRequest;
use App\Models\OnlineApplication\OvertimeApplication;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return OvertimeApplication::getFilteredData($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OvertimeRequest $request)
    {
        if(!OvertimeApplication::hasApproverSetup($request->employee_id, 'overtime_application')){
            return response()->json([
                'message' => 'No approval sequence setup found for this application.',
                'errors' => []
            ], 422);
        }

        $overtime_application = OvertimeApplication::create($request->only(['employee_id', 'created_by', 'date', 'time_from', 'time_to', 'duration', 'reason', 'allow_approver']));

        $approver_sequence_items = $overtime_application->createApproverSequence($request->employee_id, 'overtime_application');

        $overtime_application = $overtime_application->toArray();
        $overtime_application['approver_sequence_items'] = $approver_sequence_items;

        return response()->json($overtime_application, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OvertimeApplication $overtimeApplication)
    {
        $overtimeApplication->update($request->only(['employee_id', 'date', 'time_from', 'time_to', 'duration', 'reason', 'allow_approver']));

        return response()->json($overtimeApplication, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OvertimeApplication $overtimeApplication)
    {
        $overtimeApplication->delete();

        return response(null, 200);
    }
}
