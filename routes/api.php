<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OnlineApplication\CorrectionApplicationController;
use App\Http\Controllers\OnlineApplication\LeaveApplicationController;
use App\Http\Controllers\OnlineApplication\ObApplicationController;
use App\Http\Controllers\Schedule\EmployeeScheduleController;
use App\Http\Controllers\Schedule\ScheduleShiftController;
use App\Http\Controllers\Schedule\ScheduleShiftDetailsController;
use App\Http\Controllers\Dtr\UploadLogsController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get("/employees", EmployeeController::class);
    Route::get("/timesheets/{employee_id}", TimesheetController::class);

    Route::prefix("online-application")->group(function(){
        Route::apiResource("leave-applications", LeaveApplicationController::class);
        Route::apiResource("ob-applications", ObApplicationController::class);
        Route::apiResource("correction-applications", CorrectionApplicationController::class);
    });
});

Route::apiResource('/upload-logs', UploadLogsController::class);
Route::apiResource('/employee-schedule', EmployeeScheduleController::class);
Route::apiResource('/schedule-shift', ScheduleShiftController::class);
Route::apiResource('/schedule-shift-details', ScheduleShiftDetailsController::class);