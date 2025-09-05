<?php

namespace App\Http\Controllers\Dtr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dtr\EmployeeAttendanceRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $from = Carbon::parse($fromDate);
        $to = Carbon::parse($toDate);
        $period = CarbonPeriod::create($from, $to);
        $attendance = [];
        foreach($period as $date){
            $result = app(PipeLine::class)
                ->send([$date->toDateString(), $employeeId])
                ->through([
                    \App\Pipelines\Attendance\EmployeeAttendance::class,
                    \App\Pipelines\Attendance\EmployeeLeave::class,
                    \App\Pipelines\Attendance\EmployeeOfficialBusiness::class
                ])
                ->thenReturn();
            $attendance[] = $result;
        }

        return response()->json([
            'employee_id' => $employeeId,
            'records' => $attendance,
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
