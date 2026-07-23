<?php

namespace App\Services\Employee;

use App\Models\Department;
use App\Models\Office;
use App\Models\Position;
use App\Models\Team;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmployeeImportService
{
    /*
    |--------------------------------------------------------------------------
    | Kolom Template CSV
    |--------------------------------------------------------------------------
    */

    public const HEADERS = [
        'employee_number',
        'full_name',
        'email',
        'phone',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'identity_number',
        'marital_status',
        'office',
        'department',
        'position',
        'team',
        'employment_type',
        'employment_status',
        'start_date',
        'username',
        'password',
    ];

    public const EXAMPLE_ROW = [
        '', // employee_number, kosongkan untuk auto-generate
        'Budi Santoso',
        'budi.santoso@example.com',
        '081234567890',
        'Male',
        'Jakarta',
        '1995-05-17',
        'Jl. Merdeka No. 1',
        '', // identity_number, kosongkan untuk auto-generate
        'Single',
        'Head Office', // harus sama persis dengan nama Office yang sudah ada
        'Operations',   // harus sama persis dengan nama Department yang sudah ada
        'Staff',        // harus sama persis dengan nama Position yang sudah ada
        '',             // team, opsional
        'Permanent',    // Permanent / Contract / Internship
        'Active',       // Active / Probation / Resigned
        '2026-01-01',
        '', // username, kosongkan untuk auto-generate dari nama
        '', // password, kosongkan untuk auto-generate
    ];

    public function __construct(
        protected EmployeeService $employeeService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Import dari Path File CSV
    |--------------------------------------------------------------------------
    |
    | Return array of results, satu entry per baris data (bukan header),
    | masing-masing berisi status sukses/gagal + data lengkap (termasuk
    | password yang dipakai/digenerate) untuk dijadikan laporan CSV hasil.
    |
    */

    public function importFromFile(string $path): array
    {
        $companyId = Auth::user()->company_id;

        $handle = fopen($path, 'r');

        if ($handle === false) {

            throw ValidationException::withMessages([
                'file' => 'File tidak bisa dibaca.',
            ]);

        }

        $header = fgetcsv($handle);

        if (!$header) {

            fclose($handle);

            throw ValidationException::withMessages([
                'file' => 'File CSV kosong atau format tidak valid.',
            ]);

        }

        $header = array_map(
            fn ($col) => strtolower(trim((string) $col)),
            $header
        );

        // Lookup cache biar nggak query berulang-ulang tiap baris.
        $offices = Office::forCurrentCompany()->pluck('id', 'name');
        $departments = Department::forCurrentCompany()->pluck('id', 'name');
        $positions = Position::forCurrentCompany()->pluck('id', 'name');
        $teams = Team::forCurrentCompany()->pluck('id', 'name');

        $results = [];

        $rowNumber = 1; // baris 1 = header

        while (($row = fgetcsv($handle)) !== false) {

            $rowNumber++;

            // Lewati baris kosong (misal baris terakhir file).
            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $raw = [];

            foreach ($header as $index => $key) {
                $raw[$key] = isset($row[$index]) ? trim((string) $row[$index]) : '';
            }

            $results[] = $this->importRow(
                $raw,
                $rowNumber,
                $companyId,
                $offices,
                $departments,
                $positions,
                $teams
            );

        }

        fclose($handle);

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | Proses Satu Baris
    |--------------------------------------------------------------------------
    */

    private function importRow(
        array $raw,
        int $rowNumber,
        ?int $companyId,
        $offices,
        $departments,
        $positions,
        $teams
    ): array {

        $result = array_merge($raw, [
            'row' => $rowNumber,
            'status' => 'failed',
            'message' => null,
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | Validasi Dasar
            |--------------------------------------------------------------------------
            */

            if (blank($raw['full_name'] ?? null)) {
                throw new \RuntimeException('Nama (full_name) wajib diisi.');
            }

            if (blank($raw['email'] ?? null)) {
                throw new \RuntimeException('Email wajib diisi.');
            }

            if (!filter_var($raw['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Format email tidak valid.');
            }

            if (filled($raw['password'] ?? null) && strlen($raw['password']) < 8) {
                throw new \RuntimeException('Password minimal 8 karakter (atau kosongkan untuk digenerate otomatis).');
            }

            if (!in_array($raw['gender'] ?? null, ['Male', 'Female'])) {
                throw new \RuntimeException('Gender harus "Male" atau "Female".');
            }

            $officeId = $offices[$raw['office'] ?? ''] ?? null;

            if (!$officeId) {
                throw new \RuntimeException("Office \"{$raw['office']}\" tidak ditemukan.");
            }

            $departmentId = $departments[$raw['department'] ?? ''] ?? null;

            if (!$departmentId) {
                throw new \RuntimeException("Department \"{$raw['department']}\" tidak ditemukan.");
            }

            $positionId = $positions[$raw['position'] ?? ''] ?? null;

            if (!$positionId) {
                throw new \RuntimeException("Position \"{$raw['position']}\" tidak ditemukan.");
            }

            $teamId = null;

            if (filled($raw['team'] ?? null)) {

                $teamId = $teams[$raw['team']] ?? null;

                if (!$teamId) {
                    throw new \RuntimeException("Team \"{$raw['team']}\" tidak ditemukan.");
                }

            }

            $employmentType = $raw['employment_type'] ?? '';

            if (!in_array($employmentType, ['Permanent', 'Contract', 'Internship'])) {
                throw new \RuntimeException('employment_type harus Permanent/Contract/Internship.');
            }

            $employmentStatus = $raw['employment_status'] ?? '';

            if (!in_array($employmentStatus, ['Active', 'Probation', 'Resigned'])) {
                throw new \RuntimeException('employment_status harus Active/Probation/Resigned.');
            }

            if (blank($raw['start_date'] ?? null)) {
                throw new \RuntimeException('start_date wajib diisi (format YYYY-MM-DD).');
            }

            /*
            |--------------------------------------------------------------------------
            | Employee Number (auto-generate kalau kosong)
            |--------------------------------------------------------------------------
            */

            $employeeNumber = $raw['employee_number'] ?: $this->generateEmployeeNumber($companyId);

            /*
            |--------------------------------------------------------------------------
            | Username (auto-generate dari nama kalau kosong)
            |--------------------------------------------------------------------------
            */

            $username = $raw['username'] ?: $this->generateUsername($raw['full_name']);

            /*
            |--------------------------------------------------------------------------
            | Password (auto-generate kalau kosong)
            |--------------------------------------------------------------------------
            */

            $password = $raw['password'] ?: $this->generatePassword();

            $employee = $this->employeeService->create([

                'company_id' => $companyId,

                'employee_number' => $employeeNumber,

                'full_name' => $raw['full_name'],

                'email' => $raw['email'],

                'phone' => $raw['phone'] ?: null,

                'gender' => $raw['gender'],

                'birth_place' => $raw['birth_place'] ?: null,

                'birth_date' => $raw['birth_date'] ?: null,

                'address' => $raw['address'] ?: null,

                'identity_number' => $raw['identity_number'] ?: null,

                'marital_status' => $raw['marital_status'] ?: null,

                'office_id' => $officeId,

                'department_id' => $departmentId,

                'position_id' => $positionId,

                'team_id' => $teamId,

                'employment_type' => $employmentType,

                'employment_status' => $employmentStatus,

                'start_date' => $raw['start_date'],

                'username' => $username,

                'password' => $password,

            ]);

            $result['status'] = 'success';

            $result['message'] = 'Berhasil dibuat.';

            $result['employee_number'] = $employee->employee_number;

            $result['username'] = $username;

            $result['password'] = $password;

        } catch (ValidationException $e) {

            $result['message'] = collect($e->errors())->flatten()->first();

        } catch (\Throwable $e) {

            $result['message'] = $e->getMessage();

        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Generator: Employee Number
    |--------------------------------------------------------------------------
    */

    private function generateEmployeeNumber(?int $companyId): string
    {
        do {

            $number = 'EMP-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));

        } while (
            \App\Models\Employee::query()
                ->where('company_id', $companyId)
                ->where('employee_number', $number)
                ->exists()
        );

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | Generator: Username (unik global, sesuai constraint users.username)
    |--------------------------------------------------------------------------
    */

    private function generateUsername(string $fullName): string
    {
        $base = Str::slug($fullName, '.') ?: 'employee';

        $username = $base;

        $suffix = 1;

        while (\App\Models\User::where('username', $username)->exists()) {

            $suffix++;

            $username = "{$base}{$suffix}";

        }

        return $username;
    }

    /*
    |--------------------------------------------------------------------------
    | Generator: Password Acak (aman & mudah dibaca -- hindari karakter
    | yang gampang tertukar seperti 0/O, 1/l/I)
    |--------------------------------------------------------------------------
    */

    private function generatePassword(): string
    {
        $characters = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';

        $password = '';

        for ($i = 0; $i < 10; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $password;
    }
}
