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
            // Get person_ids that have at least 2 unprocessed logs
            $personIds = FacialLog::select('person_id')
                ->where('processed', 0)
                ->groupBy('person_id')
                ->havingRaw('COUNT(*) >= 2')
                ->pluck('person_id');

            foreach ($personIds as $personId) {
                // Get two earliest unprocessed logs for this person
                $logs = FacialLog::where('person_id', $personId)
                    ->where('processed', 0)
                    ->orderBy('time')
                    ->limit(2)
                    ->get();

                if ($logs->count() == 2) {
                    // Create paired log
                    PairedLog::create([
                        'person_id' => $personId,
                        'time_in' => $logs[0]->time,
                        'time_out' => $logs[1]->time,
                    ]);

                    // Mark the logs as processed
                    FacialLog::whereIn('id', $logs->pluck('id'))->update(['processed' => 1]);
                }
            }
        });
    }
}
