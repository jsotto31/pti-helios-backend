<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\EmployeeScheduleRequest as ScheduleEmployeeScheduleRequest;
use Illuminate\Http\Request;
use App\Models\Schedule\EmployeeSchedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
     * Store a newly created resource in storage.
     */
    public function store(ScheduleEmployeeScheduleRequest $request)
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

    public function show(Request $request, $employeeId)
    {   
        $schedules = EmployeeSchedule::where("employee_id", $employeeId)
                     ->when($request->date || $request->date_to || $request->date_from, function($query) use ($request){
                        $query->whereDate('date_effective', '<=', $request->date ?? $request->date_to ?? $request->date_from)
                        ->orderByRaw("FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
                        ->orderBy('date_effective', 'desc');
                     })
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
     * Update the specified resource in storage.
     */
    public function update(ScheduleEmployeeScheduleRequest $request, string $id)
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
