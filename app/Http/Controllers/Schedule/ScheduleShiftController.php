<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\ScheduleShiftRequest;
use App\Models\Schedule\ScheduleShift;
use Illuminate\Http\Request;

class ScheduleShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $scheduleName = $request->query('schedule_name');
        $schedule_shift = ScheduleShift::ForScheduleName($scheduleName)->get();
        return response()->json($schedule_shift);
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
    public function store(ScheduleShiftRequest $request)
    {
        $schedule_shift = ScheduleShift::createOrUpdateByUniqueKey($request->all());

        $results[] = [
            'data' => $schedule_shift,
            'status' => $schedule_shift->wasRecentlyCreated ? 'inserted' : 'updated',
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Schedules shift processed.',
            'results' => $results,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $schedule_shift = ScheduleShift::where('employee_id', $id)->get();
        return response()->json($schedule_shift);
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
