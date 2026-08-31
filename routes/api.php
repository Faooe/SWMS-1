<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\FirebaseAuthController;
use App\Http\Controllers\Api\V1\Attendance\AttendanceController;
use App\Http\Controllers\Api\V1\Attendance\AttendanceManagementController;
use App\Http\Controllers\Api\V1\Attendance\WorkCalendarController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\Employee\EmployeeController;
use App\Http\Controllers\Api\V1\Employee\EmployeePerformanceController;
use App\Http\Controllers\Api\V1\Employee\AssignmentController as EmployeeAssignmentController;
use App\Http\Controllers\Api\V1\Employee\LeaveRequestController as EmployeeLeaveRequestController;
use App\Http\Controllers\Api\V1\LeaveRequest\LeaveRequestController;
use App\Http\Controllers\Api\V1\Master\MasterController;
use App\Http\Controllers\Api\V1\Master\DepartmentController;
use App\Http\Controllers\Api\V1\Master\PositionController;
use App\Http\Controllers\Api\V1\Master\TeamController;
use App\Http\Controllers\Api\V1\Master\OfficeController;
use App\Http\Controllers\Api\V1\Assignment\AssignmentController;
use App\Http\Controllers\Api\V1\Assignment\AssignmentSettingsController;
use App\Http\Controllers\Api\V1\Profile\ProfileController;
use App\Http\Controllers\Api\V1\Subscription\SubscriptionController as ApiSubscriptionController;
use App\Http\Controllers\Api\V1\Platform\DashboardController as PlatformDashboardController;
use App\Http\Controllers\Api\V1\Platform\CompanyController as PlatformCompanyController;
use App\Http\Controllers\Api\V1\Platform\PremiumController as PlatformPremiumController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| CORS Preflight Safety Net
|--------------------------------------------------------------------------
|
| Jaga-jaga kalau HandleCors middleware karena suatu sebab tidak sempat
| menjawab preflight OPTIONS lebih dulu -- route ini pasti menjawab
| 204 untuk semua path di bawah /api/, supaya browser (Flutter Web,
| dsb) tidak diblokir CORS saat preflight.
|
*/
Route::options('{any}', function () {
    return response('', 204);
})->where('any', '.*');

Route::prefix('v1')->name('api.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Route
    |--------------------------------------------------------------------------
    */

    Route::post('/login', [AuthController::class, 'login']);

    // Alternatif login khusus Employee: Kode Company + NIP + Password
    // (tidak menggantikan /login di atas -- dua-duanya aktif berdampingan).
    Route::post('/login/employee', [AuthController::class, 'loginEmployee']);

    // "Login dengan Google" (Firebase SSO) versi mobile -- padanan route
    // web 'auth.firebase.login', tapi return Sanctum token. Body: id_token
    // (hasil signInWithGoogle() dari Flutter, lihat FirebaseAuthController).
    Route::post('/auth/firebase/login', [FirebaseAuthController::class, 'login']);

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

    Route::match(['get', 'post'], '/subscription/callback', [ApiSubscriptionController::class, 'callback']);

    /*
    |--------------------------------------------------------------------------
    | Protected Route
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);

        /*
        |--------------------------------------------------------------------------
        | Profile (Semua Role: Platform Admin, Company Admin, Employee)
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);

        /*
        |--------------------------------------------------------------------------
        | Attendance (Milik Sendiri -- Employee)
        |--------------------------------------------------------------------------
        */

        Route::post('/attendance/check-in', [AttendanceController::class,'checkIn']);
        Route::get('/attendance/context', [AttendanceController::class, 'context']);
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
        |
        | GET dibiarkan bisa diakses semua role yang login (mis. Employee
        | melihat daftar rekan kerja). Aksi tulis (store/update/delete/
        | toggle-status) khusus Company Admin (SUPER_ADMIN), sama seperti
        | di web (routes/web.php, grup 'superadmin').
        |
        */

        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::get('/employees/{id}', [EmployeeController::class, 'show']);

        /*
        |--------------------------------------------------------------------------
        | Employee Performance (Attendance + Assignment per Bulan)
        |--------------------------------------------------------------------------
        |
        | GET juga dibiarkan terbuka untuk semua role yang login, sama
        | seperti index/show di atas -- lihat
        | App\Http\Controllers\Api\V1\Employee\EmployeePerformanceController.
        |
        */

        Route::get('/employees/{employee}/performance', [EmployeePerformanceController::class, 'show']);
        Route::get('/employees/{employee}/performance/export/pdf', [EmployeePerformanceController::class, 'exportPdf']);
        Route::get('/employees/{employee}/performance/export/excel', [EmployeePerformanceController::class, 'exportExcel']);

        Route::middleware('role:SUPER_ADMIN')->group(function () {

            Route::post('/employees', [EmployeeController::class,'store']);
            Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
            Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
            Route::patch('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus']);

        });

        /*
        |--------------------------------------------------------------------------
        | Master Data (Dropdown, Read Only, Aktif Saja)
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
        | Master Data Management (Company Admin / Super Admin)
        |--------------------------------------------------------------------------
        |
        | Berbeda dari /master/* di atas (dropdown read-only): ini untuk
        | CRUD penuh, setara menu Department/Position/Team/Office di web.
        |
        */

        Route::middleware('role:SUPER_ADMIN')->group(function () {

            Route::apiResource('departments', DepartmentController::class);
            Route::patch('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus']);

            Route::apiResource('positions', PositionController::class);
            Route::patch('/positions/{position}/toggle-status', [PositionController::class, 'toggleStatus']);

            Route::apiResource('teams', TeamController::class);
            Route::patch('/teams/{team}/toggle-status', [TeamController::class, 'toggleStatus']);

            Route::get('/offices', [OfficeController::class, 'index']);
            Route::get('/offices/{id}', [OfficeController::class, 'show']);
            Route::put('/offices/{office}', [OfficeController::class, 'update']);

            /*
            |--------------------------------------------------------------------------
            | Attendance Management (Seluruh Karyawan Company)
            |--------------------------------------------------------------------------
            */

            Route::get('/attendance', [AttendanceManagementController::class, 'index']);
            Route::get('/attendance/statistics', [AttendanceManagementController::class, 'statistics']);
            Route::get('/attendance/analytics', [AttendanceManagementController::class, 'analytics']);
            Route::get('/attendance/calendar', [WorkCalendarController::class, 'index']);
            Route::put('/attendance/calendar/schedule', [WorkCalendarController::class, 'updateSchedule']);
            Route::post('/attendance/calendar/holidays', [WorkCalendarController::class, 'storeHoliday']);
            Route::put('/attendance/calendar/holidays/{holiday}', [WorkCalendarController::class, 'updateHoliday']);
            Route::delete('/attendance/calendar/holidays/{holiday}', [WorkCalendarController::class, 'destroyHoliday']);
            Route::get('/attendance/export/pdf', [AttendanceManagementController::class, 'exportPdf']);
            Route::get('/attendance/export/excel', [AttendanceManagementController::class, 'exportExcel']);
            Route::get('/attendance/{id}', [AttendanceManagementController::class, 'show']);

            /*
            |--------------------------------------------------------------------------
            | Leave Request Approval (Company)
            |--------------------------------------------------------------------------
            */

            Route::get('/leave-requests', [LeaveRequestController::class, 'index']);
            Route::patch('/leave-requests/{leave}/approve', [LeaveRequestController::class, 'approve']);
            Route::patch('/leave-requests/{leave}/reject', [LeaveRequestController::class, 'reject']);

        });

        /*
        |--------------------------------------------------------------------------
        | Assignment (Company / Admin Scope)
        |--------------------------------------------------------------------------
        */

        Route::get('/assignments', [AssignmentController::class, 'index']);
        Route::get('/assignments/statistics', [AssignmentController::class, 'statistics']);
        Route::get('/assignments/{id}', [AssignmentController::class, 'show']);

        Route::middleware('role:SUPER_ADMIN')->group(function () {

            Route::post('/assignments', [AssignmentController::class, 'store']);
            Route::put('/assignments/{assignment}', [AssignmentController::class, 'update']);
            Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy']);

            Route::post('/assignments/{assignment}/employees/{employeeId}/approve', [AssignmentController::class, 'approveCompletion']);
            Route::post('/assignments/{assignment}/employees/{employeeId}/reject', [AssignmentController::class, 'rejectCompletion']);

            Route::get('/assignment-settings', [AssignmentSettingsController::class, 'show']);
            Route::put('/assignment-settings', [AssignmentSettingsController::class, 'update']);

            // Self-service subscription Company Admin -- sama dengan flow web/Midtrans.
            Route::get('/subscription', [ApiSubscriptionController::class, 'show']);
            Route::post('/subscription/checkout', [ApiSubscriptionController::class, 'checkout']);

        });

        /*
        |--------------------------------------------------------------------------
        | Employee Self-Service (Role: EMPLOYEE)
        |--------------------------------------------------------------------------
        |
        | Assignment milik sendiri (accept/reject/check-in/check-out/
        | complete) dan pengajuan izin milik sendiri -- dipakai oleh
        | lib/features/employee/* di Flutter.
        |
        */

        Route::middleware('role:EMPLOYEE')->group(function () {

            Route::get('/my-assignments', [EmployeeAssignmentController::class, 'index']);
            Route::get('/my-assignments/today', [EmployeeAssignmentController::class, 'today']);
            Route::get('/my-assignments/statistics', [EmployeeAssignmentController::class, 'statistics']);
            Route::get('/my-assignments/{uuid}', [EmployeeAssignmentController::class, 'show']);
            Route::post('/my-assignments/{uuid}/accept', [EmployeeAssignmentController::class, 'accept']);
            Route::post('/my-assignments/{uuid}/reject', [EmployeeAssignmentController::class, 'reject']);
            Route::post('/my-assignments/{uuid}/check-in', [EmployeeAssignmentController::class, 'checkIn']);
            Route::post('/my-assignments/{uuid}/check-out', [EmployeeAssignmentController::class, 'checkOut']);
            Route::post('/my-assignments/{uuid}/complete', [EmployeeAssignmentController::class, 'complete']);

            Route::get('/leave-requests/mine', [EmployeeLeaveRequestController::class, 'index']);
            Route::get('/leave-requests/quota', [EmployeeLeaveRequestController::class, 'quota']);
            Route::post('/leave-requests', [EmployeeLeaveRequestController::class, 'store']);

        });

        /*
        |--------------------------------------------------------------------------
        | Platform (Platform Admin only)
        |--------------------------------------------------------------------------
        |
        | Dijaga dobel: auth:sanctum (harus login) DAN 'platform' (harus
        | role PLATFORM_ADMIN, lihat PlatformMiddleware / User::isPlatformAdmin()).
        | Dipakai oleh lib/features/platform/* di Flutter -- lihat
        | CHANGELOG_role_based_routing.md di repo mobile.
        |
        */

        Route::middleware('platform')
            ->prefix('platform')
            ->group(function () {

                Route::get('/dashboard', [
                    PlatformDashboardController::class,
                    'index'
                ]);

                Route::get('/companies', [
                    PlatformCompanyController::class,
                    'index'
                ]);

                Route::get('/companies/{id}', [
                    PlatformCompanyController::class,
                    'show'
                ]);

                Route::post('/companies', [
                    PlatformCompanyController::class,
                    'store'
                ]);

                Route::put('/companies/{company}', [
                    PlatformCompanyController::class,
                    'update'
                ]);

                Route::delete('/companies/{company}', [
                    PlatformCompanyController::class,
                    'destroy'
                ]);

                Route::patch('/companies/{company}/toggle-status', [
                    PlatformCompanyController::class,
                    'toggleStatus'
                ]);

                Route::patch('/premium/{company}', [
                    PlatformPremiumController::class,
                    'update'
                ]);

                Route::patch('/premium/{company}/cancel', [
                    PlatformPremiumController::class,
                    'cancel'
                ]);

            });

    });

});
