<?php

namespace App\Pipelines\Attendance;
use App\Services\OnlineApplication\OfficialBusinessService;
use Closure; 

class EmployeeOfficialBusiness
{
    /**
     * Create a new class instance.
     */

    public function __construct(OfficialBusinessService $officialBusinessService)
    {
        $this->officialBusinessService = $officialBusinessService;
    }

    public function handle($request, Closure $next)
    {
        [$date, $employeeId, $attendanceData, $leaveData] = $request;

        // Call the leave service to check for leaves
        $obData = $this->officialBusinessService->employeeOfficialBusiness($employeeId, $date);

        // Call the next middleware in the pipeline
        return $next([
            'employeeId' => $employeeId,
            'date' => $date,
            'attendance' => $attendanceData,
            'leave' => $leaveData,
            'officialBusiness' => $obData,
        ]);
    }
}