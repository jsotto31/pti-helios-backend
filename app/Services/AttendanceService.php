<?php
    namespace App\Services;

    use Carbon\Carbon;

    class AttendanceService
    {
        public function calculate($log, $schedule)
        {
            $status = 'present';
            $lateSeconds = 0;
            $undertimeSeconds = 0;

            $timeIn = Carbon::parse($log->time_in);
            $timeOut = Carbon::parse($log->time_out);

            $scheduleStart = Carbon::parse($schedule->start);
            $scheduleEnd = Carbon::parse($schedule->end);
            $tardyStart = Carbon::parse($schedule->tardy_start);
            $absentStart = Carbon::parse($schedule->absent_start);
            $earlyDismiss = Carbon::parse($schedule->early_dismiss);

            if ($timeIn->greaterThanOrEqualTo($absentStart)) {
                $status = 'absent';
            } elseif ($timeIn->greaterThan($tardyStart)) {
                $status = 'late';
                $lateSeconds = $timeIn->diffInSeconds($tardyStart);
            }

            if ($timeOut->lessThan($earlyDismiss)) {
                $status = $status === 'present' ? 'Early Dismissal' : "$status, Early Dismissal";
            } elseif ($scheduleEnd->greaterThan($timeOut)) {
                $status = 'undertime';
                $undertimeSeconds = $scheduleEnd->diffInSeconds($timeOut);
            }

            return [
                'employee_id' => $log->employee_id,
                'date' => $log->date,
                'sched_start' => $scheduleStart->toTimeString(),
                'sched_end' => $scheduleEnd->toTimeString(),
                'time_in' => $timeIn->toTimeString(),
                'time_out' => $timeOut->toTimeString(),
                'tardy' => $status === 'late' ? 1 : 0,
                'tardy_seconds' => $lateSeconds,
                'undertime' => $status === 'undertime' ? 1 : 0,
                'undertime_seconds' => $undertimeSeconds,
                'status' => $status,
            ];
        }
    }


?>