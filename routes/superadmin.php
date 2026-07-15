<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'superadmin',
])

->group(function () {

    Route::view(
        '/dashboard',
        'dashboard'
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    // Route::resource('employees', EmployeeController::class);

    /*
    |--------------------------------------------------------------------------
    | Office
    |--------------------------------------------------------------------------
    */

    // Route::resource('offices', OfficeController::class);

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    // Route::resource('attendances', AttendanceController::class);

    /*
    |--------------------------------------------------------------------------
    | Assignment
    |--------------------------------------------------------------------------
    */

    // Route::resource('assignments', AssignmentController::class);

});