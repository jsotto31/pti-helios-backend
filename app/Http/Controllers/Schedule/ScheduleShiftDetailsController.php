<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\ScheduleShiftDetailsRequest;
use App\Models\Schedule\ScheduleShiftDetails;
use Illuminate\Http\Request;

class ScheduleShiftDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleShiftDetailsRequest $request)
    {
        $scheduleShiftId = $request->input('schedule_shift_id');
        $schedules = $request->input('schedules');

        $results = [];

        foreach ($schedules as $schedule) {
            $schedule['schedule_shift_id'] = $scheduleShiftId;

            $result = ScheduleShiftDetails::createOrUpdateByUniqueKeys($schedule);

            $results[] = [
                'data' => $result,
                'status' => $result->wasRecentlyCreated ? 'inserted' : 'updated',
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Schedules processed.',
            'results' => $results,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $schedules = ScheduleShiftDetails::where("schedule_shift_id", $id)->get();

        if($schedules->isEmpty()){
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule not found.'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $schedules
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
