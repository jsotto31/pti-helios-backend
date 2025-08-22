<?php

namespace App\Http\Controllers\Dtr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dtr\FacialLog;
use App\Jobs\ProcessFacialLogs;

class UploadLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $maxRows = 5000;
        $headers = ['employee_id', 'time', 'device_id', 'site'];

        // Validate file
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $rows = array_map('str_getcsv', file($request->file('file')->getRealPath()));

        // Check row limits
        if (count($rows) <= 1) {
            return $this->errorResponse('CSV file is empty or missing data.', 400);
        }
        if (count($rows) - 1 > $maxRows) {
            return $this->errorResponse("CSV file exceeds maximum allowed rows of $maxRows.", 413);
        }

        unset($rows[0]); // Remove header

        $skipped = [];
        $inserted = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $i => $row) {
                $rowNumber = $i + 2;

                // Column count check
                if (count($row) !== count($headers)) {
                    $skipped[] = $this->skipReason($rowNumber, 'Incorrect number of columns');
                    continue;
                }

                $data = array_combine($headers, $row);

                // Date parse check
                if (!$this->isValidDateTime($data['time'])) {
                    $skipped[] = $this->skipReason($rowNumber, 'Invalid datetime format');
                    continue;
                }
                $data['time'] = date('Y-m-d H:i:s', strtotime($data['time']));

                // Insert attempt
                try {
                    FacialLog::create($data);

                    // trigger worker on pairing of logs
                    dispatch(new ProcessFacialLogs());

                    $inserted++;
                } catch (\Exception $e) {
                    $skipped[] = $this->skipReason($rowNumber, 'DB insert failed: ' . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'CSV import complete.',
                'inserted_rows' => $inserted,
                'skipped_rows' => $skipped,
                'total_rows' => count($rows),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to process CSV: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper: Generate skipped row reason
     */
    private function skipReason($rowNumber, $reason)
    {
        return ['row' => $rowNumber, 'reason' => $reason];
    }

    /**
     * Helper: Validate datetime format
     */
    private function isValidDateTime($date)
    {
        return strtotime($date) !== false;
    }

    /**
     * Helper: Standard error response
     */
    private function errorResponse($message, $status)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $status);
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
