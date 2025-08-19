<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineApplication\ObApplicationRequest;
use App\Models\OnlineApplication\ObApplication;
use Illuminate\Http\Request;

class ObApplicationController extends Controller
{
     public function index(Request $request){
        return ObApplication::getFilteredData($request);
    }

    public function store(ObApplicationRequest $request){
        $ob_application = ObApplication::create([
            ...$request->only(["employee_id",
                "type",
                "time_from",
                "time_to",
                "reason",
                "allow_approver",
                "created_by"
            ]), 
            'date_from' => date('Y-m-d', strtotime($request->date_from)), 
            'date_to' => date('Y-m-d', strtotime($request->date_to)),
            'time_from' => date('H:i:s', strtotime($request->time_from)),
            'time_to'   => date('H:i:s', strtotime($request->time_to))
        ]);

        return response($ob_application, 201);
    }

    public function update(ObApplication $ob_application, ObApplicationRequest $request){
        $ob_application->update([
            ...$request->only(["employee_id",
                "type",
                "time_from",
                "time_to",
                "reason",
                "allow_approver",
                "created_by"
            ]), 
            'date_from' => date('Y-m-d', strtotime($request->date_from)), 
            'date_to' => date('Y-m-d', strtotime($request->date_to)),
            'time_from' => date('H:i:s', strtotime($request->time_from)),
            'time_to'   => date('H:i:s', strtotime($request->time_to))
        ]);

        return response($ob_application, 201);
    }


    public function destroy(ObApplication $ob_application){
        $ob_application->delete();

        return response(null, 204);
    }
}
