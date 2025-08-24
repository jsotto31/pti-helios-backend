<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineApplication\CorrectionApplicationRequest;
use App\Models\OnlineApplication\CorrectionApplication;
use Illuminate\Http\Request;

class CorrectionApplicationController extends Controller
{
    public function index(Request $request){
        return CorrectionApplication::getFilteredData($request);
    }

    public function store(CorrectionApplicationRequest $request){
        if(!CorrectionApplication::hasApproverSetup($request->employee_id, 'correction_application')){
            return response()->json([
                'message' => 'No approval sequence setup found for this application.',
                'errors' => []
            ], 422);
        }

        $correction_application = CorrectionApplication::create($request->only(['employee_id', 'date', 'reason', 'allow_approver', 'created_by']));
        $correction_application->items()->createMany($request->items);

        $approver_sequence_items = $correction_application->createApproverSequence($request->employee_id, 'correction_application');

        $correction_application = $correction_application->toArray();
        $correction_application['approver_sequence_items'] = $approver_sequence_items;

        return response($correction_application, 201);
    }

    public function update(CorrectionApplication $correction_application, CorrectionApplicationRequest $request){
        $correction_application->update($request->only(['reason', 'allow_approver']));
        $correction_application->items()->delete();
        $correction_application->items()->createMany($request->items);
        return response()->json($correction_application, 200);
    }

    public function destroy(CorrectionApplication $correction_application){
        $correction_application->delete();

        return response(null, 204);
    }
}
