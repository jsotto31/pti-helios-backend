<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\EmployeeScheduleRequest;
use Illuminate\Http\Request;
use App\Models\Schedule\EmployeeSchedule;

class EmployeeScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $date_effective = $request->query('date_effective');
        
        $schedules = EmployeeSchedule::forEmployee($employeeId)
                    ->effectiveOn($date_effective)
                    ->orderBy('date_effective', 'desc')
                    ->get();

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeScheduleRequest $request)
    {
        $employeeId = $request->input('employee_id');
        $schedules = $request->input('schedules');

        $results = [];

        foreach ($schedules as $schedule) {
            $schedule['employee_id'] = $employeeId;

            $result = EmployeeSchedule::createOrUpdateByUniqueKeys($schedule);

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
   public function show(Request $request, $employeeId)
    {   
        $schedules = EmployeeSchedule::where("employee_id", $employeeId)->get();

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
    public function update(EmployeeScheduleRequest $request, string $id)
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
