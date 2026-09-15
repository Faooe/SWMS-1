<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AttendanceEmployeeGuardTest extends TestCase
{
    #[DataProvider('attendanceActions')]
    public function test_actions_keep_the_same_missing_employee_error(string $action): void
    {
        $user = new User;
        $user->setRelation('employee', null);

        try {
            app(AttendanceService::class)->{$action}($user, []);
            $this->fail('Expected a missing employee validation error.');
        } catch (ValidationException $exception) {
            $this->assertSame(
                ['employee' => ['Data karyawan tidak ditemukan.']],
                $exception->errors()
            );
        }
    }

    public static function attendanceActions(): array
    {
        return [
            'smart check-in' => ['smartCheckIn'],
            'office check-in' => ['checkIn'],
            'assignment check-in' => ['checkInAssignment'],
            'smart check-out' => ['smartCheckOut'],
            'office check-out' => ['checkOut'],
            'assignment check-out' => ['checkOutAssignment'],
        ];
    }
}
