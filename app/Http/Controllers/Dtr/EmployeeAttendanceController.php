<?php

namespace App\Http\Controllers\Dtr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dtr\EmployeeAttendanceRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Dtr\PairedLog;
use App\Models\Schedule\EmployeeSchedule;
use Illuminate\Pipeline\Pipeline;


class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeAttendanceRequest $request)
    {
        return $this->getAttendance($request->employee_id, $request->from, $request->to);
    }
    
    public function getAttendance($employeeId, $fromDate, $toDate)
    {
        $logs = PairedLog::forLogsBetweenDates($employeeId, $fromDate, $toDate)->get();

        $attendance = [];

        foreach ($logs as $log) {
            $schedule = EmployeeSchedule::effectiveForDate($employeeId, $log->date)->first();

            if (!$schedule) {
                $attendance[] = [
                    'employee_id' => $log->employee_id,
                    'date' => $log->date,
                    'sched_start' => null,
                    'sched_end' => null,
                    'time_in' => $log->time_in,
                    'time_out' => $log->time_out,
                    'tardy' => 0,
                    'tardy_seconds' => 0,
                    'undertime' => 0,
                    'undertime_seconds' => 0,
                    'status' => 'No Schedule',
                ];
            } else {
                $result = app(PipeLine::class)
                    ->send([$log, $schedule])
                    ->through([
                        \App\Pipelines\Attendance\EmployeeAttendance::class,
                        \App\Pipelines\Attendance\EmployeeLeave::class
                    ])
                    ->thenReturn();
                $attendance[] = $result;
            }
        }

        return response()->json([
            'employee_id' => $employeeId,
            'attendance' => $attendance,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
