<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Attendance\AttendanceController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\Employee\EmployeeController;
use App\Http\Controllers\Api\V1\Master\MasterController;
use App\Http\Controllers\Api\V1\Assignment\AssignmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Web\SubscriptionController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Route
    |--------------------------------------------------------------------------
    */

    Route::post('/login', [AuthController::class, 'login']);

    /*
    |--------------------------------------------------------------------------
    | Midtrans Payment Notification (Webhook)
    |--------------------------------------------------------------------------
    |
    | Dipanggil server-to-server oleh Midtrans, bukan browser -- makanya
    | ditaruh di sini (grup api, bebas CSRF) dan bukan di web.php.
    | Keamanannya murni dari signature_key (lihat MidtransService::isValidSignature).
    | Daftarkan URL ini (…/api/v1/subscription/callback) sebagai Payment
    | Notification URL di dashboard Midtrans Sandbox.
    |
    */

    Route::post('/subscription/callback', [SubscriptionController::class, 'callback']);

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
        | Notifications & Push Notification Token
        |--------------------------------------------------------------------------
        */

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::post('/notifications/fcm-token', [NotificationController::class, 'storeFcmToken']);

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