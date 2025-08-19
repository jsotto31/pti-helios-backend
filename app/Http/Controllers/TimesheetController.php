<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $employee_id)
    {
        $timesheet = Timesheet::where('employee_id', $employee_id)
                ->when($request->date, fn($query) => $query->where("work_date", date("Y-m-d", strtotime($request->date))))
                ->get();

        return response()->json($timesheet);
    }
}
