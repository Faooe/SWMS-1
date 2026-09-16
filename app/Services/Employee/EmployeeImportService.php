<?php

namespace App\Services\Employee;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Team;
use App\Models\User;
use App\Services\EmployeeService;
use App\Support\CsvDelimiterDetector;
use App\Support\StrongPasswordGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmployeeImportService
{
    public const RESULT_SUCCESS = 'success';

    public const RESULT_FAILED = 'failed';

    /** Kolom minimum agar setiap baris bisa dibuat menjadi employee lengkap. */
    public const REQUIRED_HEADERS = [
        'full_name',
        'email',
        'gender',
        'department',
        'position',
        'employment_type',
        'employment_status',
        'start_date',
    ];

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
        'marital_status',
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
        'Single',
        'Operations',   // harus sama persis dengan nama Department yang sudah ada
        'Staff',        // harus sama persis dengan nama Position yang sudah ada
        '',             // team, opsional
        'Permanent',    // Permanent / Contract / Internship
        'Active',       // Active / Resigned / Retired / Suspended
        '2026-01-01',
        '', // username, kosongkan untuk auto-generate dari nama
        '', // password, kosongkan untuk auto-generate
    ];

    public function __construct(
        protected EmployeeService $employeeService,
        protected CsvDelimiterDetector $delimiterDetector,
    ) {}

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
        $companyId = Auth::user()?->company_id;

        if (! $companyId) {
            throw ValidationException::withMessages([
                'file' => 'Akun ini belum terhubung ke company, jadi import tidak dapat diproses.',
            ]);
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {

            throw ValidationException::withMessages([
                'file' => 'File tidak bisa dibaca.',
            ]);

        }

        $delimiter = $this->delimiterDetector->detect($handle);

        $header = fgetcsv($handle, 0, $delimiter);

        if (! $header) {

            fclose($handle);

            throw ValidationException::withMessages([
                'file' => 'File CSV kosong atau format tidak valid.',
            ]);

        }

        $header = array_map(function ($col, $index) {
            $value = strtolower(trim((string) $col));

            // CSV dari Excel sering menyisipkan UTF-8 BOM di kolom pertama.
            if ($index === 0) {
                $value = preg_replace('/^'.preg_quote("\xEF\xBB\xBF", '/').'/', '', $value) ?? $value;
            }

            return $value;
        }, $header, array_keys($header));

        $duplicateHeaders = array_keys(array_filter(
            array_count_values($header),
            fn (int $count): bool => $count > 1
        ));

        if ($duplicateHeaders !== []) {
            fclose($handle);

            throw ValidationException::withMessages([
                'file' => 'Header CSV duplikat: '.implode(', ', $duplicateHeaders).'. Gunakan template resmi agar kolom tidak tertukar.',
            ]);
        }

        $missingHeaders = array_values(array_diff(self::REQUIRED_HEADERS, $header));

        if ($missingHeaders !== []) {
            fclose($handle);

            throw ValidationException::withMessages([
                'file' => 'Kolom wajib belum lengkap: '.implode(', ', $missingHeaders).'. Download template resmi lalu isi kembali.',
            ]);
        }

        // Lookup cache biar nggak query berulang-ulang tiap baris.
        $departments = $this->caseInsensitiveLookup(Department::forCurrentCompany()->pluck('id', 'name')->all());
        $positions = $this->caseInsensitiveLookup(Position::forCurrentCompany()->pluck('id', 'name')->all());
        $teams = $this->caseInsensitiveLookup(Team::forCurrentCompany()->pluck('id', 'name')->all());

        $results = [];

        $rowNumber = 1; // baris 1 = header

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {

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
        $departments,
        $positions,
        $teams
    ): array {

        $result = array_merge($raw, [
            'row' => $rowNumber,
            'status' => self::RESULT_FAILED,
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

            $raw['email'] = Str::lower(trim((string) $raw['email']));

            if (! filter_var($raw['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Format email tidak valid.');
            }

            if (filled($raw['password'] ?? null) && ! StrongPasswordGenerator::meetsPolicy((string) $raw['password'])) {
                throw new \RuntimeException('Password minimal 8 karakter dan harus memiliki huruf besar, huruf kecil, serta angka (atau kosongkan untuk digenerate otomatis).');
            }

            $raw['gender'] = $this->normalizeEnum($raw['gender'] ?? null, [
                'male' => 'Male',
                'female' => 'Female',
            ]);

            if (! $raw['gender']) {
                throw new \RuntimeException('Gender harus "Male" atau "Female".');
            }

            $departmentId = $this->lookup($departments, $raw['department'] ?? '');

            if (! $departmentId) {
                throw new \RuntimeException("Department \"{$raw['department']}\" tidak ditemukan.");
            }

            $positionId = $this->lookup($positions, $raw['position'] ?? '');

            if (! $positionId) {
                throw new \RuntimeException("Position \"{$raw['position']}\" tidak ditemukan.");
            }

            $teamId = null;

            if (filled($raw['team'] ?? null)) {

                $teamId = $this->lookup($teams, $raw['team']);

                if (! $teamId) {
                    throw new \RuntimeException("Team \"{$raw['team']}\" tidak ditemukan.");
                }

            }

            $employmentType = $this->normalizeEnum($raw['employment_type'] ?? null, [
                'permanent' => 'Permanent',
                'contract' => 'Contract',
                'internship' => 'Internship',
            ]);

            if (! $employmentType) {
                throw new \RuntimeException('employment_type harus Permanent/Contract/Internship.');
            }

            $employmentStatus = $this->normalizeEnum($raw['employment_status'] ?? null, [
                'active' => 'Active',
                'resigned' => 'Resigned',
                'retired' => 'Retired',
                'suspended' => 'Suspended',
            ]);

            if (! $employmentStatus) {
                throw new \RuntimeException('employment_status harus Active/Resigned/Retired/Suspended.');
            }

            if (blank($raw['start_date'] ?? null)) {
                throw new \RuntimeException('start_date wajib diisi (format YYYY-MM-DD).');
            }

            if (! $this->isValidDate($raw['start_date'])) {
                throw new \RuntimeException('start_date harus memakai format YYYY-MM-DD yang valid.');
            }

            if (filled($raw['birth_date'] ?? null) && ! $this->isValidDate($raw['birth_date'])) {
                throw new \RuntimeException('birth_date harus memakai format YYYY-MM-DD yang valid.');
            }

            if (filled($raw['marital_status'] ?? null)) {
                $raw['marital_status'] = $this->normalizeEnum($raw['marital_status'], [
                    'single' => 'Single',
                    'married' => 'Married',
                ]);

                if (! $raw['marital_status']) {
                    throw new \RuntimeException('marital_status harus Single atau Married.');
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Employee Number (auto-generate kalau kosong)
            |--------------------------------------------------------------------------
            */

            $employeeNumber = $raw['employee_number'] ?: $this->generateEmployeeNumber($companyId);

            if (Employee::withTrashed()
                ->where('company_id', $companyId)
                ->where('employee_number', $employeeNumber)
                ->exists()) {
                throw new \RuntimeException("Employee number \"{$employeeNumber}\" sudah digunakan di company ini.");
            }

            if (Employee::withTrashed()
                ->where('company_id', $companyId)
                ->where('email', $raw['email'])
                ->exists()) {
                throw new \RuntimeException("Email employee \"{$raw['email']}\" sudah digunakan di company ini.");
            }

            if (User::withTrashed()->where('email', $raw['email'])->exists()) {
                throw new \RuntimeException("Email login \"{$raw['email']}\" sudah digunakan.");
            }

            /*
            |--------------------------------------------------------------------------
            | Username (auto-generate dari nama kalau kosong)
            |--------------------------------------------------------------------------
            */

            $username = $raw['username'] ?: $this->generateUsername($raw['full_name'], $companyId);

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

                'marital_status' => $raw['marital_status'] ?: null,

                'department_id' => $departmentId,

                'position_id' => $positionId,

                'team_id' => $teamId,

                'employment_type' => $employmentType,

                'employment_status' => $employmentStatus,

                'start_date' => $raw['start_date'],

                'username' => $username,

                'password' => $password,

            ]);

            $result['status'] = self::RESULT_SUCCESS;

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
    | Generator: Employee Number (NIP)
    |--------------------------------------------------------------------------
    |
    | Sequential per company, pakai Kode Company sebagai prefix (mis.
    | "PTX-0001", "PTX-0002", dst) supaya gampang dibaca/diingat -- bukan
    | string acak seperti sebelumnya (EMP-260731-A1B2). Ini cuma dipakai
    | kalau kolom employee_number di CSV dikosongkan; company tetap bebas
    | isi NIP sendiri secara manual per baris kalau mau format lain.
    |--------------------------------------------------------------------------
    */

    private function generateEmployeeNumber(?int $companyId): string
    {
        $prefix = Company::query()
            ->where('id', $companyId)
            ->value('code') ?: 'EMP';

        $next = Employee::query()
            ->where('company_id', $companyId)
            ->count() + 1;

        do {

            $number = $prefix.'-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);

            $next++;

        } while (
            Employee::query()
                ->where('company_id', $companyId)
                ->where('employee_number', $number)
                ->exists()
        );

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | Generator: Username
    |--------------------------------------------------------------------------
    |
    | Username BUKAN kredensial login (login pakai Email atau NIP+Kode
    | Company), jadi boleh sama/kembar antar employee -- termasuk dalam
    | 1 company yang sama. Fungsi ini cuma bikin slug rapi dari nama,
    | TANPA cek keunikan/tambah suffix angka.
    |--------------------------------------------------------------------------
    */
    private function generateUsername(string $fullName, ?int $companyId): string
    {
        return Str::slug($fullName, '.') ?: 'employee';
    }

    /*
    |--------------------------------------------------------------------------
    | Generator: Password Acak (aman & mudah dibaca -- hindari karakter
    | yang gampang tertukar seperti 0/O, 1/l/I)
    |--------------------------------------------------------------------------
    */

    private function generatePassword(): string
    {
        return StrongPasswordGenerator::generate();
    }

    /** @param array<string, mixed> $values */
    private function caseInsensitiveLookup(array $values): array
    {
        $lookup = [];

        foreach ($values as $name => $id) {
            $lookup[$this->normalizeLookupKey($name)] = $id;
        }

        return $lookup;
    }

    private function lookup(array $values, ?string $value): mixed
    {
        return $values[$this->normalizeLookupKey($value)] ?? null;
    }

    private function normalizeLookupKey(?string $value): string
    {
        return Str::lower(trim((string) $value));
    }

    /** @param array<string, string> $allowed */
    private function normalizeEnum(?string $value, array $allowed): ?string
    {
        return $allowed[$this->normalizeLookupKey($value)] ?? null;
    }

    private function isValidDate(string $value): bool
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', trim($value));
        $errors = \DateTimeImmutable::getLastErrors();

        return $date !== false
            && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))
            && $date->format('Y-m-d') === trim($value);
    }
}
