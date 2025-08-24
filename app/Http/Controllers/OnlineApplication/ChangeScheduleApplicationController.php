<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineApplication\ChangeScheduleApplicationRequest;
use App\Models\OnlineApplication\ChangeScheduleApplication;
use Illuminate\Http\Request;

class ChangeScheduleApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        return ChangeScheduleApplication::getFilteredData($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChangeScheduleApplicationRequest $request)
    {
        if(!ChangeScheduleApplication::hasApproverSetup($request->employee_id, 'change_schedule_application')){
            return response()->json([
                'message' => 'No approval sequence setup found for this application.',
                'errors' => []
            ], 422);
        }

        $change_schedule_application = ChangeScheduleApplication::create($request->only(['employee_id', 'type', 'date', 'date_from', 'date_to', 'allow_approver', 'created_by']));

        $change_schedule_application->items = $change_schedule_application->items()->createMany($request->items);

        $approver_sequence_items = $change_schedule_application->createApproverSequence($request->employee_id, 'change_schedule_application');

        $change_schedule_application = $change_schedule_application->toArray();
        $change_schedule_application['approver_sequence_items'] = $approver_sequence_items;

        return response()->json($change_schedule_application, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChangeScheduleApplicationRequest $request, ChangeScheduleApplication $changeScheduleApplication)
    {
        $changeScheduleApplication->update($request->only(['employee_id', 'type', 'date', 'date_from', 'date_to', 'allow_approver']));
        $changeScheduleApplication->items()->delete();
        $changeScheduleApplication->items = $changeScheduleApplication->items()->createMany($request->items);

        return response()->json($changeScheduleApplication, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChangeScheduleApplication $changeScheduleApplication)
    {
        $changeScheduleApplication->items()->delete();
        $changeScheduleApplication->delete();

        return response(null, 204);
    }
}
