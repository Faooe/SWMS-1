<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Attendance\AttendanceController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\Employee\EmployeeController;
use App\Http\Controllers\Api\V1\Master\MasterController;
use App\Http\Controllers\Api\V1\Assignment\AssignmentController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Route
    |--------------------------------------------------------------------------
    */

    Route::post('/login', [AuthController::class, 'login']);

    /*
    |--------------------------------------------------------------------------
    | Protected Route
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/attendance/check-in', [AttendanceController::class,'checkIn']);
        Route::get('/attendance/today',[AttendanceController::class, 'today']);
        Route::get('/attendance/history', [AttendanceController::class,'history']);
        Route::post('/attendance/check-out',[AttendanceController::class, 'checkOut']);
        Route::get('/dashboard', [DashboardController::class,'index']);
        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */
        Route::get('/employees', [
            EmployeeController::class,
            'index'
        ]);
        Route::get('/employees/{id}', [
            EmployeeController::class,
            'show'
        ]);
        Route::post('/employees', [EmployeeController::class,'store']);
        /*
        |--------------------------------------------------------------------------
        | Master Data
        |--------------------------------------------------------------------------
        */
        Route::prefix('master')->group(function () {

            Route::get('/departments', [MasterController::class, 'departments']);

            Route::get('/positions', [MasterController::class, 'positions']);

            Route::get('/teams', [MasterController::class, 'teams']);

            Route::get('/offices', [MasterController::class, 'offices']);

            Route::get('/shifts', [MasterController::class, 'shifts']);

        });
        /*
        |--------------------------------------------------------------------------
        | Assignment
        |--------------------------------------------------------------------------
        */

        Route::get('/assignments', [
            AssignmentController::class,
            'index'
        ]);

        Route::get('/assignments/{id}', [
            AssignmentController::class,
            'show'
        ]);

        Route::post('/assignments', [
            AssignmentController::class,
            'store'
        ]);

    });

});