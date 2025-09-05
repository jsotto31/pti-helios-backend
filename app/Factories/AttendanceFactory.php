<?php

namespace App\Factories;

use Carbon\Carbon;

class AttendanceFactory
{
    /**
     * Create a default attendance record (e.g. no logs, no schedule).
     */
    public static function makeDefault(
        $employeeId,
        $date,
        $status,
        ?string $timeIn = null,
        ?string $timeOut = null,
        ?string $schedStart = null,
        ?string $schedEnd = null
    ): array {
        return [
            'employee_id'        => $employeeId,
            'date'               => Carbon::parse($date)->format('F d, Y'),
            'sched_start'        => $schedStart ? Carbon::parse($schedStart)->format('h:i A') : null,
            'sched_end'          => $schedEnd ? Carbon::parse($schedEnd)->format('h:i A') : null,
            'time_in'            => $timeIn ? Carbon::parse($timeIn)->format('h:i A') : null,
            'time_out'           => $timeOut ? Carbon::parse($timeOut)->format('h:i A') : null,
            'tardy'              => 0,
            'tardy_seconds'      => 0,
            'undertime'          => 0,
            'undertime_seconds'  => 0,
            'status'             => self::formatRemarks($status),
        ];
    }

    /**
     * Create an attendance record from a schedule + log.
     */
    public static function makeFromScheduleAndLog($schedule, $log): array
    {
        $scheduleStart   = Carbon::parse($schedule->start);
        $scheduleEnd     = Carbon::parse($schedule->end);
        $tardyStart      = Carbon::parse($schedule->tardy_start);
        $absentStart     = Carbon::parse($schedule->absent_start);
        $earlyDismiss    = Carbon::parse($schedule->early_dismiss);

        $timeIn  = Carbon::parse($log['time_in']);
        $timeOut = Carbon::parse($log['time_out']);

        $status           = 'present';
        $lateSeconds      = 0;
        $undertimeSeconds = 0;

        // Check tardiness / absence
        if ($timeIn->greaterThanOrEqualTo($absentStart)) {
            $status = 'absent';
        } elseif ($timeIn->greaterThan($tardyStart)) {
            $status .= '|late';
            $lateSeconds = $timeIn->diffInSeconds($tardyStart);
        }

        // Check undertime / early dismissal
        if ($timeOut->lessThan($earlyDismiss)) {
            $status .= ($status === 'present') 
                ? '|early_dismissal' 
                : ', early_dismissal';
        } elseif ($timeOut->lessThan($scheduleEnd)) {
            $status .= '|undertime';
            $undertimeSeconds = $scheduleEnd->diffInSeconds($timeOut);
        }

        return [
            'sched_start'        => $scheduleStart->format('h:i A'),
            'sched_end'          => $scheduleEnd->format('h:i A'),
            'time_in'            => $timeIn->format('h:i A'),
            'time_out'           => $timeOut->format('h:i A'),
            'tardy'              => str_contains($status, 'late') ? 1 : 0,
            'tardy_seconds'      => $lateSeconds,
            'undertime'          => str_contains($status, 'undertime') ? 1 : 0,
            'undertime_seconds'  => $undertimeSeconds,
            'status'             => self::formatRemarks($status),
        ];
    }

    /**
     * Apply HTML color formatting to attendance status.
     */
    private static function formatRemarks(string $status): string
    {
        $parts = explode('|', $status);
        $formatted = [];

        foreach ($parts as $part) {
            $part = trim(strtolower($part));

            switch ($part) {
                case 'present':
                    $formatted[] = "<span style='color:green;'>Present</span>";
                    break;
                case 'late':
                case 'undertime':
                    $formatted[] = "<span style='color:orange;'>" . ucfirst($part) . "</span>";
                    break;
                case 'early_dismissal':
                    $formatted[] = "<span style='color:red;'>Early Dismissal</span>";
                    break;
                case 'absent':
                    $formatted[] = "<span style='color:red;'>Absent</span>";
                    break;
                default:
                    $formatted[] = ucfirst($part);
            }
        }

        return implode(', ', $formatted);
    }
}
