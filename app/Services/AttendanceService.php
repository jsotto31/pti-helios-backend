<?php

namespace App\Services;

use App\Helpers\AttendanceHelper;
use App\Models\Timesheet;
use App\Models\Schedule\EmployeeSchedule;
use App\Factories\AttendanceFactory;
use App\Models\OnlineApplication\CorrectionApplication;

class AttendanceService
{
    /**
     * Calculate attendance details for a given employee on a specific date.
     *
     * @param  string  $date
     * @param  string|int $employeeId
     * @return array
     */
    public function calculate(string $date, string $employeeId): array
    {
        $schedules = EmployeeSchedule::effectiveForDate($employeeId, $date)->get();
        $results = [];

        foreach ($schedules as $schedule) {
            // Fetch original logs
            $timesheets = Timesheet::getLogsForEmployeeOnDate($employeeId, $date);

            // Override with corrections if available
            $corrections = CorrectionApplication::getCorrectionsForEmployeeOnDate($employeeId, $date, "approved");
            if (!empty($corrections)) {
                $timesheets = AttendanceHelper::transformTimesheet($corrections);
            }

            // Handle cases
            if (empty($timesheets)) {
                $results[] = AttendanceFactory::makeDefault(
                    $employeeId,
                    $date,
                    'No Logs',
                    null,
                    null,
                    $schedule->start,
                    $schedule->end
                );
                continue;
            }

            $results[] = AttendanceFactory::makeFromScheduleAndLog(
                $schedule,
                collect($timesheets)->first()
            );
        }

        // No schedules case (explicit)
        if ($schedules->isEmpty()) {
            $results[] = AttendanceFactory::makeDefault($employeeId, $date, 'No Schedule');
        }
        
        return $results;
    }
}
