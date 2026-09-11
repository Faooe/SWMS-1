<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Team;
use App\Models\User;
use App\Services\Attendance\WorkCalendarService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class PtNafarinEmployeeDemoSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'nfarinn1@gmail.com';

    private const PASSWORD = '12345b1';

    private const START_DATE = '2026-07-01';

    private const END_DATE = '2026-09-10';

    /**
     * Seed 50 employee demo beserta attendance dan assignment historis.
     *
     * Seeder ini sengaja tidak dipanggil dari DatabaseSeeder karena hanya
     * ditujukan untuk data demo PT Nafarin. Jalankan secara eksplisit:
     * php artisan db:seed --class=Database\\Seeders\\PtNafarinEmployeeDemoSeeder
     */
    public function run(): void
    {
        $admin = User::query()
            ->whereRaw('LOWER(email) = ?', [self::ADMIN_EMAIL])
            ->first();

        if (! $admin?->company_id) {
            throw new RuntimeException('Admin PT Nafarin '.self::ADMIN_EMAIL.' tidak ditemukan.');
        }

        $company = Company::query()->findOrFail($admin->company_id);
        $office = Office::query()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->orderByDesc('is_head_office')
            ->orderBy('id')
            ->first();
        $employeeRole = Role::query()->where('code', 'EMPLOYEE')->first();
        $teams = Team::query()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
        $departments = Department::query()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');
        $positions = Position::query()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
        $shift = Shift::query()
            ->where('code', 'PAGI')
            ->where('is_active', true)
            ->first();

        if (! $office || ! $employeeRole || $teams->isEmpty() || $departments->isEmpty() || $positions->isEmpty() || ! $shift) {
            throw new RuntimeException('Master data PT Nafarin belum lengkap (office, role, department, position, team, atau shift pagi).');
        }

        $teams = $teams
            ->filter(fn (Team $team): bool => $departments->has($team->department_id))
            ->values();

        if ($teams->isEmpty()) {
            throw new RuntimeException('Tidak ada team PT Nafarin yang terhubung ke department aktif.');
        }

        $workingDates = $this->workingDates($company);
        $profiles = $this->employeeProfiles();
        $now = now();
        $passwordHash = Hash::make(self::PASSWORD);

        DB::transaction(function () use (
            $admin,
            $company,
            $office,
            $employeeRole,
            $teams,
            $positions,
            $shift,
            $workingDates,
            $profiles,
            $now,
            $passwordHash,
        ): void {
            $employeeRows = [];

            foreach ($profiles as $index => $profile) {
                $number = $index + 1;

                $employeeRows[] = [
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $company->id,
                    'employee_number' => $profile['employee_number'],
                    'full_name' => $profile['name'],
                    'email' => $profile['email'],
                    'phone' => '081250'.str_pad((string) $number, 5, '0', STR_PAD_LEFT),
                    'gender' => $number % 2 === 0 ? 'Female' : 'Male',
                    'birth_place' => ['Banjarmasin', 'Banjarbaru', 'Martapura'][$index % 3],
                    'birth_date' => sprintf('%d-%02d-%02d', 1990 + ($index % 10), ($index % 12) + 1, ($index % 27) + 1),
                    'address' => 'Alamat dummy employee '.$number.', Banjarmasin, Kalimantan Selatan',
                    'marital_status' => $number % 3 === 0 ? 'Married' : 'Single',
                    'photo' => null,
                    'emergency_contact_name' => 'Kontak Darurat '.$profile['name'],
                    'emergency_contact_phone' => '081251'.str_pad((string) $number, 5, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
            }

            DB::table('employees')->upsert(
                $employeeRows,
                ['company_id', 'employee_number'],
                [
                    'full_name', 'email', 'phone', 'gender', 'birth_place',
                    'birth_date', 'address', 'marital_status', 'photo',
                    'emergency_contact_name', 'emergency_contact_phone',
                    'is_active', 'updated_at', 'deleted_at',
                ],
            );

            $employees = Employee::withTrashed()
                ->where('company_id', $company->id)
                ->whereIn('employee_number', $profiles->pluck('employee_number'))
                ->get()
                ->keyBy('employee_number');

            $userRows = [];
            $historyRows = [];

            foreach ($profiles as $index => $profile) {
                $employee = $employees->get($profile['employee_number']);

                if (! $employee) {
                    throw new RuntimeException('Gagal membuat '.$profile['employee_number'].'.');
                }

                $team = $teams[$index % $teams->count()];
                $position = $positions[$index % $positions->count()];

                $userRows[] = [
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $company->id,
                    'employee_id' => $employee->id,
                    'role_id' => $employeeRole->id,
                    'username' => $profile['username'],
                    'email' => $profile['email'],
                    'email_verified_at' => $now,
                    'password' => $passwordHash,
                    'password_changed_at' => null,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];

                $historyRows[] = [
                    'employee_id' => $employee->id,
                    'department_id' => $team->department_id,
                    'position_id' => $position->id,
                    'team_id' => $team->id,
                    'office_id' => $office->id,
                    'shift_id' => $shift->id,
                    'supervisor_id' => null,
                    'employment_type' => ['Permanent', 'Contract', 'Internship'][$index % 3],
                    'employment_status' => 'Active',
                    'start_date' => CarbonImmutable::parse('2025-01-06')->addDays($index)->toDateString(),
                    'end_date' => null,
                    'is_current' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('users')->upsert(
                $userRows,
                ['email'],
                [
                    'company_id', 'employee_id', 'role_id', 'username',
                    'email_verified_at', 'password', 'password_changed_at',
                    'is_active', 'updated_at', 'deleted_at',
                ],
            );

            $employeeIds = $employees->pluck('id')->values();

            DB::table('employment_histories')->whereIn('employee_id', $employeeIds)->delete();
            DB::table('employment_histories')->insert($historyRows);

            DB::table('attendances')
                ->whereIn('employee_id', $employeeIds)
                ->whereBetween('attendance_date', [self::START_DATE, self::END_DATE])
                ->delete();

            $attendanceRows = $this->attendanceRows(
                $company->id,
                $office,
                $shift,
                $employees,
                $profiles,
                $workingDates,
            );

            foreach (array_chunk($attendanceRows, 250) as $chunk) {
                DB::table('attendances')->insert($chunk);
            }

            Assignment::withTrashed()
                ->where('company_id', $company->id)
                ->where('assignment_number', 'like', 'NF-DMY-%')
                ->forceDelete();

            [$assignmentRows, $assignmentBlueprints] = $this->assignmentRows(
                $admin,
                $company->id,
                $office,
                $employees,
                $profiles,
                $workingDates,
            );

            foreach (array_chunk($assignmentRows, 100) as $chunk) {
                DB::table('assignments')->insert($chunk);
            }

            $assignmentIds = Assignment::query()
                ->where('company_id', $company->id)
                ->whereIn('assignment_number', collect($assignmentBlueprints)->pluck('assignment_number'))
                ->pluck('id', 'assignment_number');
            $pivotRows = [];

            foreach ($assignmentBlueprints as $blueprint) {
                $assignmentId = $assignmentIds->get($blueprint['assignment_number']);

                if (! $assignmentId) {
                    throw new RuntimeException('Gagal membuat assignment '.$blueprint['assignment_number'].'.');
                }

                $pivotRows[] = $this->assignmentPivotRow(
                    $assignmentId,
                    $blueprint,
                    $admin->id,
                );
            }

            DB::table('assignment_employees')->insert($pivotRows);
        });

        $this->command?->info('Dummy PT Nafarin berhasil dibuat.');
        $this->command?->table(
            ['No', 'Employee', 'Habit', 'Email', 'Username', 'Password'],
            $profiles->map(fn (array $profile, int $index): array => [
                $index + 1,
                $profile['name'],
                $profile['habit_label'],
                $profile['email'],
                $profile['username'],
                self::PASSWORD,
            ])->all(),
        );
    }

    private function workingDates(Company $company): Collection
    {
        $calendar = app(WorkCalendarService::class);
        $dates = collect();
        $cursor = CarbonImmutable::parse(self::START_DATE, $company->timezone ?? 'Asia/Makassar');
        $end = CarbonImmutable::parse(self::END_DATE, $company->timezone ?? 'Asia/Makassar');

        while ($cursor->lessThanOrEqualTo($end)) {
            if ($calendar->isWorkingDay($company, $cursor)) {
                $dates->push($cursor);
            }

            $cursor = $cursor->addDay();
        }

        if ($dates->isEmpty()) {
            throw new RuntimeException('Tidak ada hari kerja PT Nafarin pada periode dummy.');
        }

        return $dates;
    }

    private function attendanceRows(
        int $companyId,
        Office $office,
        Shift $shift,
        Collection $employees,
        Collection $profiles,
        Collection $workingDates,
    ): array {
        $rows = [];

        foreach ($profiles as $employeeIndex => $profile) {
            $employee = $employees->get($profile['employee_number']);

            foreach ($workingDates as $dateIndex => $date) {
                $selector = ($employeeIndex * 13 + $dateIndex * 7) % 100;
                $status = $this->attendanceStatus($profile['habit'] ?? 'normal', $selector);
                $worked = in_array($status, ['Present', 'Late'], true);
                $checkIn = null;
                $checkOut = null;
                $lateMinutes = 0;
                $workMinutes = 0;
                $overtimeMinutes = 0;

                if ($worked) {
                    if ($status === 'Late') {
                        $checkInMinute = 16 + ($selector % 25);
                        $checkIn = sprintf('08:%02d:00', $checkInMinute);
                        $lateMinutes = $checkInMinute;
                    } else {
                        $checkInMinute = 48 + ($selector % 12);
                        $checkIn = sprintf('07:%02d:00', $checkInMinute);
                    }

                    $checkOutMinute = $selector % 21;
                    $checkOut = sprintf('17:%02d:00', $checkOutMinute);
                    $checkInTotal = ($status === 'Late' ? 8 * 60 : 7 * 60) + $checkInMinute;
                    $checkOutTotal = 17 * 60 + $checkOutMinute;
                    $workMinutes = max(0, $checkOutTotal - $checkInTotal - 60);
                    $overtimeMinutes = $checkOutMinute;
                }

                $distance = $worked ? 6 + ($selector % 35) : null;
                $createdAt = $date->setTime(17, 30);

                $rows[] = [
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'employee_id' => $employee->id,
                    'office_id' => $office->id,
                    'assignment_id' => null,
                    'shift_id' => $shift->id,
                    'attendance_type' => 'OFFICE',
                    'attendance_date' => $date->toDateString(),
                    'check_in_time' => $checkIn,
                    'check_in_latitude' => $worked ? $office->latitude : null,
                    'check_in_longitude' => $worked ? $office->longitude : null,
                    'check_in_distance' => $distance,
                    'check_out_time' => $checkOut,
                    'check_out_latitude' => $worked ? $office->latitude : null,
                    'check_out_longitude' => $worked ? $office->longitude : null,
                    'check_out_distance' => $distance,
                    'check_in_photo' => null,
                    'check_out_photo' => null,
                    'allowed_radius' => $office->radius,
                    'location_verified' => $worked,
                    'attendance_status' => $status,
                    'is_checked_in' => $worked,
                    'is_checked_out' => $worked,
                    'late_minutes' => $lateMinutes,
                    'work_minutes' => $workMinutes,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => $overtimeMinutes,
                    'notes' => $this->attendanceNote($status),
                    'daily_report_notes' => $worked ? 'Menyelesaikan pekerjaan harian sesuai rencana tim. [DUMMY PT NAFARIN]' : null,
                    'daily_report_photos' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                    'deleted_at' => null,
                ];
            }
        }

        return $rows;
    }

    private function assignmentRows(
        User $admin,
        int $companyId,
        Office $office,
        Collection $employees,
        Collection $profiles,
        Collection $workingDates,
    ): array {
        $rows = [];
        $blueprints = [];
        $datesByMonth = $workingDates->groupBy(fn (CarbonImmutable $date): string => $date->format('m'));
        $months = ['07', '08', '09'];
        $titles = [
            'Inspeksi Perangkat Kantor',
            'Pemeliharaan Jaringan Internal',
            'Pemeriksaan Inventaris Operasional',
            'Survey Kesiapan Area Kerja',
            'Instalasi Perangkat Pendukung',
            'Penanganan Kendala Operasional',
        ];
        $types = ['Inspection', 'Maintenance', 'Survey', 'Installation', 'Emergency'];
        $priorities = ['Critical', 'High', 'Medium', 'Low'];

        foreach ($profiles as $employeeIndex => $profile) {
            $employee = $employees->get($profile['employee_number']);

            foreach ($months as $monthIndex => $month) {
                /** @var Collection<int, CarbonImmutable> $monthDates */
                $monthDates = $datesByMonth->get($month, collect())->values();

                if ($monthDates->isEmpty()) {
                    continue;
                }

                $date = $monthDates[($employeeIndex * 3 + $monthIndex * 2) % $monthDates->count()];
                $start = $date->setTime(9 + (($employeeIndex + $monthIndex) % 2), 0);
                $end = $start->addHours(6);
                $assignedAt = $start->subDay()->setTime(10, 0);
                $scenario = $this->assignmentScenario(
                    $profile['habit'] ?? 'normal',
                    $employeeIndex,
                    $monthIndex,
                );
                $assignmentNumber = sprintf('NF-DMY-26%s-%03d', $month, $employeeIndex + 1);
                $globalStatus = in_array($scenario, [0, 1, 4, 5], true) ? 'Completed' : 'Assigned';

                $rows[] = [
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'assignment_number' => $assignmentNumber,
                    'title' => $titles[($employeeIndex + $monthIndex) % count($titles)],
                    'description' => 'Assignment dummy untuk simulasi histori kerja employee PT Nafarin.',
                    'office_id' => $office->id,
                    'location_name' => $office->name,
                    'address' => $office->address,
                    'latitude' => $office->latitude,
                    'longitude' => $office->longitude,
                    'radius' => $office->radius,
                    'polygon' => null,
                    'priority' => $priorities[($employeeIndex + $monthIndex) % count($priorities)],
                    'assignment_type' => $types[($employeeIndex + $monthIndex) % count($types)],
                    'status' => $globalStatus,
                    'start_datetime' => $start,
                    'end_datetime' => $end,
                    'daily_attendance_enabled' => false,
                    'attendance_day_rule' => 'WORK_CALENDAR',
                    'created_by' => $admin->id,
                    'created_at' => $assignedAt,
                    'updated_at' => $end,
                    'deleted_at' => null,
                ];

                $blueprints[] = [
                    'assignment_number' => $assignmentNumber,
                    'employee_id' => $employee->id,
                    'scenario' => $scenario,
                    'assigned_at' => $assignedAt,
                    'start' => $start,
                    'end' => $end,
                ];
            }
        }

        return [$rows, $blueprints];
    }

    private function assignmentPivotRow(int $assignmentId, array $blueprint, int $reviewerId): array
    {
        $scenario = $blueprint['scenario'];
        $completed = in_array($scenario, [0, 1, 4, 5], true);
        $rejected = $scenario === 3;
        $notWorked = $scenario === 2;
        $reviewStatus = match ($scenario) {
            0, 5 => 'Approved',
            1 => 'Pending Review',
            2 => 'Not Worked',
            4 => 'Needs Revision',
            default => null,
        };
        $reviewed = in_array($reviewStatus, ['Approved', 'Needs Revision'], true);
        $reviewedAt = $reviewed ? $blueprint['end']->addHours(2) : null;

        return [
            'assignment_id' => $assignmentId,
            'employee_id' => $blueprint['employee_id'],
            'status' => $completed ? 'Completed' : ($rejected ? 'Rejected' : 'Assigned'),
            'assigned_at' => $blueprint['assigned_at'],
            'accepted_at' => $completed ? $blueprint['assigned_at']->addHour() : null,
            'started_at' => $completed ? $blueprint['start'] : null,
            'work_check_in_at' => $completed ? $blueprint['start'] : null,
            'work_check_out_at' => $completed ? $blueprint['end'] : null,
            'finished_at' => $completed ? $blueprint['end'] : null,
            'notes' => $notWorked ? 'Employee tidak memulai assignment hingga batas waktu. [DUMMY]' : 'Data assignment dummy PT Nafarin.',
            'rejection_reason' => $rejected ? 'Employee menolak karena bentrok dengan pekerjaan prioritas lain. [DUMMY]' : null,
            'completion_photo' => null,
            'completion_photo_2' => null,
            'completion_notes' => $completed ? 'Pekerjaan telah dilaksanakan dan hasilnya dilaporkan. [DUMMY]' : null,
            'review_status' => $reviewStatus,
            'review_notes' => match ($reviewStatus) {
                'Approved' => 'Hasil pekerjaan disetujui. [DUMMY]',
                'Needs Revision' => 'Hasil perlu diperbaiki dan dikirim ulang. [DUMMY]',
                'Not Worked' => 'Assignment berakhir tanpa aktivitas pengerjaan. [DUMMY]',
                default => null,
            },
            'reviewed_by' => $reviewed ? $reviewerId : null,
            'reviewed_at' => $reviewedAt,
            'revision_deadline_at' => $reviewStatus === 'Needs Revision' ? $reviewedAt?->addHour() : null,
            'is_late_revision' => false,
            'revision_count' => $reviewStatus === 'Needs Revision' ? 1 : 0,
            'created_at' => $blueprint['assigned_at'],
            'updated_at' => $reviewedAt ?? $blueprint['end'],
        ];
    }

    private function attendanceNote(string $status): string
    {
        return match ($status) {
            'Absent' => 'Tidak hadir tanpa check-in. [DUMMY PT NAFARIN]',
            'Leave' => 'Cuti terjadwal. [DUMMY PT NAFARIN]',
            'Permission' => 'Izin tidak masuk kerja. [DUMMY PT NAFARIN]',
            'Late' => 'Hadir terlambat dan menyelesaikan jam kerja. [DUMMY PT NAFARIN]',
            default => 'Hadir sesuai jadwal kerja. [DUMMY PT NAFARIN]',
        };
    }

    private function attendanceStatus(string $habit, int $selector): string
    {
        return match ($habit) {
            // Employee rajin: hampir selalu hadir dan jarang terlambat.
            'diligent' => match (true) {
                $selector < 1 => 'Absent',
                $selector < 3 => 'Leave',
                $selector < 6 => 'Permission',
                $selector < 12 => 'Late',
                default => 'Present',
            },
            // Employee yang perlu perhatian: lebih sering absen/izin dan terlambat.
            'needs_attention' => match (true) {
                $selector < 18 => 'Absent',
                $selector < 24 => 'Leave',
                $selector < 32 => 'Permission',
                $selector < 55 => 'Late',
                default => 'Present',
            },
            // Pola normal mempertahankan distribusi dummy sebelumnya.
            default => match (true) {
                $selector < 5 => 'Absent',
                $selector < 8 => 'Leave',
                $selector < 12 => 'Permission',
                $selector < 27 => 'Late',
                default => 'Present',
            },
        };
    }

    private function assignmentScenario(string $habit, int $employeeIndex, int $monthIndex): int
    {
        $seed = $employeeIndex + $monthIndex * 2;

        return match ($habit) {
            // Lebih banyak selesai; sesekali masuk pending review/revisi.
            'diligent' => [0, 5, 1, 0, 4, 5][$seed % 6],
            // Distribusi seimbang.
            'normal' => $seed % 6,
            // Lebih banyak assignment yang ditolak atau tidak dikerjakan.
            'needs_attention' => [2, 3, 4, 2, 3, 1][$seed % 6],
            default => $seed % 6,
        };
    }

    private function employeeProfiles(): Collection
    {
        $names = [
            'Ahmad Rizky Pratama', 'Siti Aisyah Ramadhani', 'Muhammad Fajar Hidayat',
            'Nurul Hikmah', 'Andi Saputra', 'Dewi Lestari', 'Rizky Maulana',
            'Putri Maharani', 'Arif Rahman', 'Nabila Zahra', 'Dimas Setiawan',
            'Anisa Fitriani', 'Bayu Kurniawan', 'Rina Oktaviani', 'Ilham Akbar',
            'Maya Sari', 'Reza Firmansyah', 'Indah Permata', 'Yoga Pratama',
            'Aulia Rahman', 'Farhan Maulana', 'Citra Dewi', 'Wahyu Hidayat',
            'Nadia Safitri', 'Gilang Ramadhan', 'Elsa Maharani', 'Hafiz Fauzan',
            'Intan Nuraini', 'Aditya Nugroho', 'Syifa Amalia',
            'Bima Prakoso', 'Luthfi Ramadhan', 'Kevin Alvaro', 'Taufik Haryanto',
            'Yuni Kartika', 'Melati Anggraini', 'Fikri Adinata', 'Sarah Amelia',
            'Rafi Kurnia', 'Wulan Sari', 'Bintang Nugraha', 'Vina Aprillia',
            'Joko Susanto', 'Laila Nurfadila', 'Rendra Wijaya', 'Nisa Kamilah',
            'Bagas Saputro', 'Tika Maharani', 'Agus Setiawan', 'Salsabila Putri',
        ];

        return collect($names)->map(function (string $name, int $index): array {
            $number = $index + 1;
            $suffix = str_pad((string) $number, 2, '0', STR_PAD_LEFT);
            $habit = match (true) {
                $number >= 31 && $number <= 36 => 'diligent',
                $number >= 45 => 'needs_attention',
                default => 'normal',
            };
            $habitLabel = match ($habit) {
                'diligent' => 'Rajin',
                'needs_attention' => 'Perlu perhatian',
                default => 'Normal',
            };

            return [
                'employee_number' => 'NF-DMY-'.str_pad((string) $number, 3, '0', STR_PAD_LEFT),
                'name' => $name,
                'email' => 'employee'.$suffix.'.ptnafarin@swms.test',
                'username' => 'ptn.employee'.$suffix,
                'habit' => $habit,
                'habit_label' => $habitLabel,
            ];
        });
    }
}
