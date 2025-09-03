<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Dtr\FacialLog;
use App\Models\Dtr\PairedLog;

class ProcessFacialLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        // You can pass parameters here if needed
    }

    public function handle()
    {
        // Use transaction to keep data consistent
        DB::transaction(function () {
            // Get employee_ids that have at least 2 unprocessed logs
            $employeeIds = FacialLog::UnprocessedLogs();

            foreach ($employeeIds as $employeeId) {
                // Get two earliest unprocessed logs for this person
                $logs = FacialLog::ForEmployeeUnprocessedLogs($employeeId)->get();

                if ($logs->count() == 2) {
                    $date = date("Y-m-d", strtotime($logs[0]->time));
                    $from = $logs[0]->time;
                    $to = $logs[1]->time;

                    // Create paired log
                    PairedLog::create([
                        'employee_id' => $employeeId,
                        'date' => $date,
                        'time_in' => $from,
                        'time_out' => $to,
                    ]);

                    // Mark the logs as processed
                    FacialLog::whereIn('id', $logs->pluck('id'))->update(['processed' => 1]);
                }
            }
        });
    }
}
