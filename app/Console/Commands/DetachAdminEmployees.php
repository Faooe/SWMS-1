<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetachAdminEmployees extends Command
{
    /*
    |--------------------------------------------------------------------------
    | One-off Cleanup
    |--------------------------------------------------------------------------
    |
    | Sebelumnya, setiap Company Administrator (SUPER_ADMIN) yang dibuat lewat
    | company registration otomatis mendapat record Employee + EmploymentHistory
    | juga (supaya bisa absen dsb). Itu sudah dihapus dari alur registrasi baru,
    | tapi company yang SUDAH terlanjur dibuat sebelum perubahan ini masih
    | punya data lama tersebut -- makanya admin masih muncul di Employee
    | Management & Attendance mereka.
    |
    | Command ini membersihkan data lama itu: melepas employee_id dari user
    | SUPER_ADMIN/PLATFORM_ADMIN, lalu menghapus Employee + EmploymentHistory +
    | Attendance miliknya. Dijalankan SEKALI SAJA, manual, lewat:
    |
    |   php artisan admins:detach-employee            (dry-run, cuma preview)
    |   php artisan admins:detach-employee --force     (beneran dieksekusi)
    |
    */

    protected $signature = 'admins:detach-employee {--force : Benar-benar jalankan perubahan (default hanya preview)}';

    protected $description = 'Lepas & bersihkan record Employee lama milik user SUPER_ADMIN / PLATFORM_ADMIN (data peninggalan sebelum admin berhenti dianggap employee).';

    public function handle(): int
    {
        $users = User::query()

            ->whereNotNull('employee_id')

            ->whereHas('role', function ($query) {

                $query->whereIn('code', [
                    'SUPER_ADMIN',
                    'PLATFORM_ADMIN',
                ]);

            })

            ->with('employee')

            ->get();

        if ($users->isEmpty()) {

            $this->info('Tidak ada data admin yang perlu dibersihkan.');

            return self::SUCCESS;

        }

        $this->table(
            ['User ID', 'Username', 'Company ID', 'Employee ID', 'Employee Number'],
            $users->map(fn (User $user) => [
                $user->id,
                $user->username,
                $user->company_id,
                $user->employee_id,
                $user->employee?->employee_number,
            ]),
        );

        if (!$this->option('force')) {

            $this->warn('Ini masih preview. Jalankan ulang dengan --force untuk benar-benar menghapus data di atas.');

            return self::SUCCESS;

        }

        if (!$this->confirm('Yakin mau lepas & hapus semua record Employee di atas? Ini tidak bisa dibatalkan.')) {

            $this->info('Dibatalkan.');

            return self::SUCCESS;

        }

        DB::transaction(function () use ($users) {

            foreach ($users as $user) {

                $employeeId = $user->employee_id;

                $user->forceFill(['employee_id' => null])->save();

                if (!$employeeId) {
                    continue;
                }

                Attendance::where('employee_id', $employeeId)->delete();

                EmploymentHistory::where('employee_id', $employeeId)->delete();

                Employee::whereKey($employeeId)->delete();

            }

        });

        $this->info("Selesai. {$users->count()} akun admin dibersihkan dari data Employee lama.");

        return self::SUCCESS;

    }
}
