<?php

namespace App\Pipelines\Attendance;
use App\Services\AttendanceService;
use Closure; 

class EmployeeAttendance
{
    /**
     * Create a new class instance.
     */
    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function handle($request, Closure $next)
    {
        [$date, $employeeId] = $request;

        // Call the attendance service to calculate attendance data.
        $attendanceData = $this->attendanceService->calculate($date, $employeeId);
        // Call the next middleware in the pipeline
        return $next([$date, $employeeId, $attendanceData]);
    }
}
