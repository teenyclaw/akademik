<?php

use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\ClassScheduleController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->name('api.v1.')->group(function () {
    Route::get('/user', fn (\Illuminate\Http\Request $request) => $request->user());

    Route::apiResource('students', StudentController::class)->only(['index', 'show']);
    Route::apiResource('grades', GradeController::class)->only(['index', 'show']);
    Route::apiResource('attendances', AttendanceController::class)->only(['index', 'show']);
    Route::apiResource('class-schedules', ClassScheduleController::class)->only(['index', 'show']);
    Route::apiResource('announcements', AnnouncementController::class)->only(['index', 'show']);
});
