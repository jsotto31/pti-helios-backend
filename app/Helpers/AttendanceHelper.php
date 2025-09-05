<?php

namespace App\Helpers;

class AttendanceHelper
{
    /**
     * Transform correction records into a standardized timesheet array.
     *
     * @param  array $corrections
     * @return array
     */
    public static function transformTimesheet(array $corrections): array
    {
        return collect($corrections)->map(function ($correction) {
            return [
                'time_in' => $correction['request_time_in'] !== '00:00:00'
                    ? $correction['request_time_in']
                    : $correction['actual_time_in'],

                'time_out' => $correction['request_time_out'] !== '00:00:00'
                    ? $correction['request_time_out']
                    : $correction['actual_time_out'],
            ];
        })->all();
    }
}
