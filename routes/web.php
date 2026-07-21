<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Controller
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Web\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Platform Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Platform\DashboardController as PlatformDashboardController;
use App\Http\Controllers\Platform\CompanyController;
use App\Http\Controllers\Platform\ProfileController as PlatformProfileController;
use App\Http\Controllers\Web\Employee\ProfileController as EmployeeProfileController;
use App\Http\Controllers\Platform\PremiumController;

/*
|--------------------------------------------------------------------------
| Super Admin Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\AssignmentController;
use App\Http\Controllers\Web\OfficeController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\LeaveRequestController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\PositionController;
use App\Http\Controllers\Web\TeamController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Employee Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Web\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Web\Employee\AttendanceController as EmployeeAttendanceController;
use App\Http\Controllers\Web\Employee\AssignmentController as EmployeeAssignmentController;
use App\Http\Controllers\Web\Employee\LeaveRequestController as EmployeeLeaveRequestController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'index']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'authenticate']
    )->name('login.authenticate');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post(
        '/logout',
        [LoginController::class, 'logout']
    )->name('logout');

});



/*
|--------------------------------------------------------------------------
| PLATFORM ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'platform',
])
->prefix('platform')
->name('platform.')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [PlatformDashboardController::class, 'index']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Company Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
    'companies',
    CompanyController::class
);

Route::patch(
    'companies/{company}/toggle-status',
    [CompanyController::class, 'toggleStatus']
)->name('companies.toggle-status');

/*
|--------------------------------------------------------------------------
| Premium Management
|--------------------------------------------------------------------------
*/

Route::prefix('premium')

    ->name('premium.')

    ->group(function () {

        Route::get(
            '/',
            [PremiumController::class, 'index']
        )->name('index');

        Route::patch(
            '/{company}',
            [PremiumController::class, 'update']
        )->name('update');

        Route::patch(
            '/{company}/cancel',
            [PremiumController::class, 'cancel']
        )->name('cancel');

    });

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [PlatformProfileController::class, 'edit']
    )->name('profile.edit');

    Route::put(
        '/profile',
        [PlatformProfileController::class, 'update']
    )->name('profile.update');

});



/*
|--------------------------------------------------------------------------
| SUPER ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'superadmin',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [DashboardController::class, 'index']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Notifications (Bell / Badge)
    |--------------------------------------------------------------------------
    */

    Route::prefix('notifications')
        ->name('notifications.')
        ->group(function () {

            Route::get('/', [NotificationController::class, 'index'])
                ->name('index');

            Route::get('/unread-count', [NotificationController::class, 'unreadCount'])
                ->name('unread-count');

            Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])
                ->name('read');

            Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])
                ->name('read-all');

        });

    /*
    |--------------------------------------------------------------------------
    | Employee Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'employees',
        EmployeeController::class
    );

    Route::patch(
        'employees/{employee}/toggle-status',
        [EmployeeController::class, 'toggleStatus']
    )->name('employees.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Office Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'offices',
        OfficeController::class
    );

    /*
    |--------------------------------------------------------------------------
    | Department Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'departments',
        DepartmentController::class
    );

    Route::patch(
        'departments/{department}/toggle-status',
        [DepartmentController::class, 'toggleStatus']
    )->name('departments.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Position Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'positions',
        PositionController::class
    );

    Route::patch(
        'positions/{position}/toggle-status',
        [PositionController::class, 'toggleStatus']
    )->name('positions.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Team Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'teams',
        TeamController::class
    );

    Route::patch(
        'teams/{team}/toggle-status',
        [TeamController::class, 'toggleStatus']
    )->name('teams.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Attendance Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'attendance',
        AttendanceController::class
    )->only([
        'index',
        'show',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Attendance Export
    |--------------------------------------------------------------------------
    */

    Route::get(
        'attendance/export/pdf',
        [AttendanceController::class, 'exportPdf']
    )->name('attendance.export.pdf');

    Route::get(
        'attendance/export/excel',
        [AttendanceController::class, 'exportExcel']
    )->name('attendance.export.excel');

    /*
    |--------------------------------------------------------------------------
    | Leave / Permission Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        'leaves',
        [LeaveRequestController::class, 'index']
    )->name('leaves.index');

    Route::patch(
        'leaves/{leave}/approve',
        [LeaveRequestController::class, 'approve']
    )->name('leaves.approve');

    Route::patch(
        'leaves/{leave}/reject',
        [LeaveRequestController::class, 'reject']
    )->name('leaves.reject');

    /*
    |--------------------------------------------------------------------------
    | Assignment Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'assignments',
        AssignmentController::class
    );

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::get(
    '/profile',
    [ProfileController::class, 'edit']
)->name('profile.edit');

Route::put(
    '/profile',
    [ProfileController::class, 'update']
)->name('profile.update');

});



/*
|--------------------------------------------------------------------------
| EMPLOYEE AREA
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'employee',
])
->prefix('employee')
->name('employee.')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [EmployeeDashboardController::class, 'index']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/attendance',
        [EmployeeAttendanceController::class, 'index']
    )->name('attendance.index');

    Route::post(
        '/attendance/check-in',
        [EmployeeAttendanceController::class, 'checkIn']
    )->name('attendance.check-in');

    Route::post(
        '/attendance/check-out',
        [EmployeeAttendanceController::class, 'checkOut']
    )->name('attendance.check-out');

    Route::get(
        '/attendance/history',
        [EmployeeAttendanceController::class, 'history']
    )->name('attendance.history');

    /*
    |--------------------------------------------------------------------------
    | Leave / Permission
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/leaves',
        [EmployeeLeaveRequestController::class, 'index']
    )->name('leaves.index');

    Route::post(
        '/leaves',
        [EmployeeLeaveRequestController::class, 'store']
    )->name('leaves.store');

    /*
    |--------------------------------------------------------------------------
    | Assignment
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/assignments',
        [EmployeeAssignmentController::class, 'index']
    )->name('assignments.index');

    Route::get(
        '/assignments/{uuid}',
        [EmployeeAssignmentController::class, 'show']
    )->name('assignments.show');

    /*
    |--------------------------------------------------------------------------
    | Assignment Actions
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/assignments/{uuid}/accept',
        [EmployeeAssignmentController::class, 'accept']
    )->name('assignments.accept');

    Route::post(
        '/assignments/{uuid}/reject',
        [EmployeeAssignmentController::class, 'reject']
    )->name('assignments.reject');

    Route::post(
        '/assignments/{uuid}/check-in',
        [EmployeeAssignmentController::class, 'checkIn']
    )->name('assignments.check-in');

    Route::post(
        '/assignments/{uuid}/check-out',
        [EmployeeAssignmentController::class, 'checkOut']
    )->name('assignments.check-out');

    Route::post(
        '/assignments/{uuid}/complete',
        [EmployeeAssignmentController::class, 'complete']
    )->name('assignments.complete');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [EmployeeProfileController::class, 'edit']
    )->name('profile');

    Route::put(
        '/profile',
        [EmployeeProfileController::class, 'update']
    )->name('profile.update');

});

/*
|--------------------------------------------------------------------------
| Cron Trigger Routes
|--------------------------------------------------------------------------
| Endpoint HTTP untuk memicu scheduled command (mark-absent, activate
| assignments, dll) karena hosting serverless tidak menjalankan Laravel
| Scheduler otomatis. Lihat routes/cron.php dan CronController.
*/

require __DIR__.'/cron.php';