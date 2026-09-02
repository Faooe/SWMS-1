<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Secure File Serving (Signed URL)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\SecureFileController;

/*
|--------------------------------------------------------------------------
| Authentication Controller
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\FirebaseLoginController;

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
use App\Http\Controllers\Web\WorkCalendarController;
use App\Http\Controllers\Web\AssignmentController;
use App\Http\Controllers\Web\AssignmentSettingsController;
use App\Http\Controllers\Web\OfficeController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\LeaveRequestController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\PositionController;
use App\Http\Controllers\Web\TeamController;
use App\Http\Controllers\Web\SubscriptionController;
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

Route::get(

    '/files/{path}',

    [SecureFileController::class, 'show']

)
    ->where('path', '.*')
    ->middleware('signed')
    ->name('files.show');


// Landing page publik setelah pembayaran Midtrans dari aplikasi mobile.
// Tidak membutuhkan session web; user cukup kembali ke aplikasi lalu status
// subscription akan di-refresh dari API.
Route::view('/subscription/mobile-finish', 'subscription.mobile-finish')
    ->name('subscription.mobile-finish');

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'index']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'authenticate']
    )->middleware('throttle:login')->name('login.authenticate');

    Route::post(
        '/auth/firebase/login',
        [FirebaseLoginController::class, 'login']
    )->middleware('throttle:login')->name('auth.firebase.login');

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

    /*
    |--------------------------------------------------------------------------
    | Notifications (Bell / Badge)
    |--------------------------------------------------------------------------
    |
    | Dipindah ke sini (dari grup SUPER ADMIN AREA) supaya Platform Admin
    | & Employee juga bisa akses -- endpoint-nya sendiri sudah otomatis
    | scoped ke user yang login (lihat NotificationController), jadi
    | aman dipakai lintas role.
    |
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

    Route::post('/profile/photo', [PlatformProfileController::class, 'updatePhoto'])
        ->name('profile.photo');

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
    | Employee Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        'employees/import',
        [EmployeeController::class, 'import']
    )->name('employees.import');

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
    | Employee Performance (Attendance + Assignment per Bulan)
    |--------------------------------------------------------------------------
    */

    Route::get(
        'employees/{employee}/performance',
        [EmployeeController::class, 'performance']
    )->name('employees.performance');

    Route::get(
        'employees/{employee}/performance/export/pdf',
        [EmployeeController::class, 'performanceExportPdf']
    )->name('employees.performance.export.pdf');

    Route::get(
        'employees/{employee}/performance/export/excel',
        [EmployeeController::class, 'performanceExportExcel']
    )->name('employees.performance.export.excel');

    /*
    |--------------------------------------------------------------------------
    | Office Management
    |--------------------------------------------------------------------------
    */
    Route::get(
        'offices',
        [OfficeController::class, 'index']
    )->name('offices.index');

    Route::get(
        'offices/{office}/edit',
        [OfficeController::class, 'edit']
    )->name('offices.edit');

    Route::put(
        'offices/{office}',
        [OfficeController::class, 'update']
    )->name('offices.update');

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

    Route::get('attendance/calendar', [WorkCalendarController::class, 'index'])->name('attendance.calendar');
    Route::put('attendance/calendar/schedule', [WorkCalendarController::class, 'updateSchedule'])->name('attendance.calendar.schedule');
    Route::post('attendance/calendar/holidays', [WorkCalendarController::class, 'storeHoliday'])->name('attendance.calendar.holidays.store');
    Route::get('attendance/calendar/holidays/{holiday}/edit', [WorkCalendarController::class, 'editHoliday'])->name('attendance.calendar.holidays.edit');
    Route::put('attendance/calendar/holidays/{holiday}', [WorkCalendarController::class, 'updateHoliday'])->name('attendance.calendar.holidays.update');
    Route::delete('attendance/calendar/holidays/{holiday}', [WorkCalendarController::class, 'destroyHoliday'])->name('attendance.calendar.holidays.destroy');

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

    Route::post(
        'assignments/{assignment}/employees/{employeeId}/approve',
        [AssignmentController::class, 'approveCompletion']
    )->name('assignments.completion.approve');

    Route::post(
        'assignments/{assignment}/employees/{employeeId}/reject',
        [AssignmentController::class, 'rejectCompletion']
    )->name('assignments.completion.reject');

    Route::post('assignments/{assignment}/checkout-corrections/{correction}/approve', [AssignmentController::class, 'approveCheckoutCorrection'])->name('assignments.checkout-corrections.approve');
    Route::post('assignments/{assignment}/checkout-corrections/{correction}/reject', [AssignmentController::class, 'rejectCheckoutCorrection'])->name('assignments.checkout-corrections.reject');

    /*
    |--------------------------------------------------------------------------
    | Assignment Settings (durasi revisi default & Auto Approve)
    |--------------------------------------------------------------------------
    */

    Route::get(
        'assignment-settings',
        [AssignmentSettingsController::class, 'edit']
    )->name('assignment-settings.edit');

    Route::put(
        'assignment-settings',
        [AssignmentSettingsController::class, 'update']
    )->name('assignment-settings.update');

    /*
    |--------------------------------------------------------------------------
    | Subscription (Self-Service Upgrade / Midtrans Snap)
    |--------------------------------------------------------------------------
    */

    Route::prefix('subscription')
        ->name('subscription.')
        ->group(function () {

            Route::get(
                '/',
                [SubscriptionController::class, 'index']
            )->name('index');

            Route::post(
                '/checkout',
                [SubscriptionController::class, 'checkout']
            )->name('checkout');

            Route::get(
                '/finish',
                [SubscriptionController::class, 'finish']
            )->name('finish');

        });

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

Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
    ->name('profile.photo');

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

    Route::post('/assignments/{uuid}/checkout-corrections', [EmployeeAssignmentController::class, 'requestCheckoutCorrection'])->name('assignments.checkout-corrections.store');

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

    Route::post('/profile/photo', [EmployeeProfileController::class, 'updatePhoto'])
        ->name('profile.photo');

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