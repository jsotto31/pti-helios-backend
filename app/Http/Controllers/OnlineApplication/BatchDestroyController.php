<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class BatchDestroyController extends Controller implements HasMiddleware
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            "type" => ['required', 'in:leave_application,overtime_application,ob_application,correction_application,change_schedule_application']
        ]);
        
        $model = $this->onlineApplication($request->type);

        $model::whereIn('id', $request->ids)->delete();

        return response(null, 204);
    }

    private function onlineApplication($type){
        return match($type) {
            'leave_application' => \App\Models\OnlineApplication\LeaveApplication::class,
            'overtime_application' => \App\Models\OnlineApplication\OvertimeApplication::class,
            'ob_application' => \App\Models\OnlineApplication\OBApplication::class,
            'correction_application' => \App\Models\OnlineApplication\CorrectionApplication::class,
            'change_schedule_application' => \App\Models\OnlineApplication\ChangeScheduleApplication::class,
            default => null,
        };
    }

    public static function middleware(): array
    {
        return ['must.be.admin'];
    }
}
