<?php

namespace App\Pipelines\Attendance;
use App\Services\OnlineApplication\LeaveService;
use Closure; 

class EmployeeLeave
{
    /**
     * Create a new class instance.
     */

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function handle($request, Closure $next)
    {
        [$date, $employeeId, $attendanceData] = $request;

        // Call the leave service to check for leaves
        $leaveData = $this->leaveService->employeeLeave($employeeId, $date);

        // Call the next middleware in the pipeline
        return $next([
            $date,
            $employeeId,
            $attendanceData,
            $leaveData,
        ]);
    }
}