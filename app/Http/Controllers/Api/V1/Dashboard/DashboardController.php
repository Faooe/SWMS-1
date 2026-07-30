<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\DashboardService;
use App\Services\EmployeeAssignmentService;
use App\Services\EmployeeDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
        protected EmployeeDashboardService $employeeDashboardService,
        protected AttendanceService $attendanceService,
        protected EmployeeAssignmentService $employeeAssignmentService
    ) {
    }

    /**
     * Dashboard.
     *
     * Sebelumnya endpoint ini SELALU memakai DashboardService (data
     * ringkasan company: total_employee, attendance_today, dsb) untuk
     * role apapun -- termasuk EMPLOYEE, padahal role EMPLOYEE seharusnya
     * melihat dashboard pribadi (assignment & absensi miliknya sendiri,
     * lihat app/Http/Controllers/Web/Employee/DashboardController.php).
     * Sekarang endpoint ini bercabang sesuai role user yang login supaya
     * hasilnya konsisten dengan versi web-nya.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return ResponseHelper::error(
                'Unauthenticated.',
                null,
                401
            );

        }

        if ($user->isEmployee()) {

            $data = $this->employeeDashboardService->index($user);

            $data['today_attendance'] = $this->attendanceService->today($user);

            $data['attendance_statistics'] = $this->attendanceService->statistics($user);

            $data['today_assignments'] = $this->employeeAssignmentService->today($user);

            $data['assignment_statistics'] = $this->employeeAssignmentService->statistics($user);

            return ResponseHelper::success(
                $data,
                'Dashboard berhasil diambil.'
            );

        }

        return ResponseHelper::success(

            $this->dashboardService->index($user),

            'Dashboard berhasil diambil.'

        );
    }
}
