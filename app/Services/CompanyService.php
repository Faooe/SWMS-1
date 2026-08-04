<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use App\Services\SecureFileService;

use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PositionSeeder;
use Database\Seeders\TeamSeeder;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyService
{
    /*
    |--------------------------------------------------------------------------
    | Company List
    |--------------------------------------------------------------------------
    */
    public function getAll(
        array $filters = []
    ): LengthAwarePaginator {

        $query = Company::query()

            ->with([

                'users.role',

                'users.employee',

            ])

            ->withCount([

                'users',

                'employees',

                'offices',

                'assignments',

                'attendances',

            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($query) use ($search) {

                $query

                    ->where(
                        'code',
                        'ILIKE',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'name',
                        'ILIKE',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'email',
                        'ILIKE',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'city',
                        'ILIKE',
                        "%{$search}%"
                    );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['status']) &&
            $filters['status'] !== ''
        ) {

            $query->where(

                'is_active',

                filter_var(

                    $filters['status'],

                    FILTER_VALIDATE_BOOLEAN

                )

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Subscription
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['plan'])) {

            $query->where(

                'subscription_plan',

                $filters['plan']

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        return $query

            ->orderBy('name')

            ->paginate(

                $filters['per_page'] ?? 10

            )

            ->withQueryString();

    }

    /*
    |--------------------------------------------------------------------------
    | Find Company
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): Company {

        return Company::query()

            ->with([

                'users.role',

                'users.employee',

                'employees',

                'offices',

                'headOffice',

            ])

            ->withCount([

                'users',

                'employees',

                'offices',

                'assignments',

                'attendances',

            ])

            ->findOrFail($id);

    }

    /*
    |--------------------------------------------------------------------------
    | Create Company
    |--------------------------------------------------------------------------
    */

    public function create(array $data): array
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Generate Password
            |--------------------------------------------------------------------------
            */

            $plainPassword = $this->generatePassword();

            /*
            |--------------------------------------------------------------------------
            | Create Company
            |--------------------------------------------------------------------------
            */

            $company = $this->createCompany($data);

            /*
            |--------------------------------------------------------------------------
            | Seed Default Master Data (Department, Position, Team)
            |--------------------------------------------------------------------------
            |
            | Setiap company baru mendapatkan set Department/Position/Team
            | starter miliknya sendiri, terpisah dari company lain.
            |
            */

            $this->seedMasterData($company);

            /*
            |--------------------------------------------------------------------------
            | Create Head Office
            |--------------------------------------------------------------------------
            */

            $this->createHeadOffice(

                $company,

                $data

            );

            /*
            |--------------------------------------------------------------------------
            | Create Super Admin
            |--------------------------------------------------------------------------
            */

            $user = $this->createSuperAdmin(

                $company,

                $data,

                $plainPassword

            );

            /*
            |--------------------------------------------------------------------------
            | Return Result
            |--------------------------------------------------------------------------
            */

            return [

                'company' => $company->fresh(['headOffice']),

                'username' => $user->username,

                'email' => $user->email,

                'password' => $plainPassword,

            ];

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update Company
    |--------------------------------------------------------------------------
    */

    public function update(
        Company $company,
        array $data
    ): Company {

        return DB::transaction(function () use (
            $company,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | Upload New Logo
            |--------------------------------------------------------------------------
            */

            $logo = $company->logo;

            if (!empty($data['logo'])) {

                $this->deleteLogo(
                    $company->logo
                );

                $logo = $this->uploadLogo(
                    $data['logo']
                );

            }

            /*
            |--------------------------------------------------------------------------
            | Update Company
            |--------------------------------------------------------------------------
            */

            $company->update([

                'code' => strtoupper(
                    trim($data['code'])
                ),

                'name' => trim(
                    $data['name']
                ),

                'email' => $data['email'] ?? null,

                'phone' => $data['phone'] ?? null,

                'website' => $data['website'] ?? null,

                'logo' => $logo,

                'address' => $data['address'] ?? null,

                'city' => $data['city'] ?? null,

                'province' => $data['province'] ?? null,

                'postal_code' => $data['postal_code'] ?? null,

                'timezone' => $data['timezone']
                    ?? 'Asia/Makassar',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Head Office
            |--------------------------------------------------------------------------
            |
            | BUG SEBELUMNYA: latitude & longitude yang diubah lewat form
            | (mis. geser pin di peta) TIDAK PERNAH ikut disimpan ke sini
            | -- cuma 'polygon' yang di-update, jadi titik lokasi company
            | selalu "nyangkut" di nilai waktu company pertama kali dibuat,
            | walaupun form-nya kelihatan berhasil disimpan (field lain
            | seperti address/city memang ikut berubah, makanya tidak
            | langsung ketahuan). Sekarang latitude/longitude ikut
            | disimpan -- kalau tidak dikirim (null), pakai nilai lama
            | supaya tidak sengaja ke-reset ke kosong.
            |
            */

            /*
            |--------------------------------------------------------------------------
            | Sync Super Admin Login Credentials
            |--------------------------------------------------------------------------
            |
            | BUG SEBELUMNYA: field admin_email / admin_username di form edit
            | sudah divalidasi (unique, dsb) tapi TIDAK PERNAH benar-benar
            | disimpan ke tabel users -- cuma nyangkut di request lalu dibuang.
            | Akibatnya company.email (email kontak perusahaan) berubah,
            | tapi akun login Super Admin (users.email) tetap yang lama,
            | jadi login pakai email baru selalu gagal. Sekarang kalau
            | admin_email / admin_username dikirim, langsung sync ke user
            | Super Admin milik company ini.
            |
            */

            $this->syncSuperAdmin(
                $company,
                $data
            );

            $currentHeadOffice = $company->headOffice;

            $company->offices()

            ->where('is_head_office', true)

            ->update([

                'name' => $company->name . ' - Head Office',

                'address' => $company->address,

                'city' => $company->city,

                'province' => $company->province,

                'postal_code' => $company->postal_code,

                'timezone' => $company->timezone,

                'latitude' => $data['latitude']
                    ?? $currentHeadOffice?->latitude
                    ?? 0,

                'longitude' => $data['longitude']
                    ?? $currentHeadOffice?->longitude
                    ?? 0,

                'polygon' => $this->decodePolygon($data['polygon'] ?? null),

            ]);

            return $company->fresh(['headOffice']);

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Delete Company
    |--------------------------------------------------------------------------
    */

    public function delete(
        Company $company
    ): bool {

        return DB::transaction(function () use (
            $company
        ) {

            /*
            |--------------------------------------------------------------------------
            | Delete Logo
            |--------------------------------------------------------------------------
            */

            $this->deleteLogo(
                $company->logo
            );

            /*
            |--------------------------------------------------------------------------
            | Delete Company
            |--------------------------------------------------------------------------
            */

            return (bool) $company->delete();

        });

    }

   /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
{
    return [

        'total' => Company::count(),

        'active' => Company::where(

            'is_active',

            true

        )->count(),

        'inactive' => Company::where(

            'is_active',

            false

        )->count(),

        'free' => Company::where(

            'subscription_plan',

            'Free'

        )->count(),

        'premium' => Company::where(

            'subscription_plan',

            '!=',

            'Free'

        )->count(),

        'enterprise' => Company::where(

            'subscription_plan',

            'Enterprise'

        )->count(),

        'expired' => Company::where(

            'subscription_plan',

            '!=',

            'Free'

        )

        ->whereNotNull('subscription_end')

        ->whereDate(

            'subscription_end',

            '<',

            today()

        )->count(),

        'employees' => Employee::count(),

    ];

}
    /*
    |--------------------------------------------------------------------------
    | Toggle Company Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        Company $company
    ): Company {

        $company->update([

            'is_active' => !$company->is_active,

        ]);

        return $company->fresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Update Subscription
    |--------------------------------------------------------------------------
    */

    public function updateSubscription(
        Company $company,
        string $plan,
        string $duration
    ): Company {

        $plans = config('plans');

        if (!isset($plans[$plan])) {

            throw new \InvalidArgumentException('Plan tidak dikenali.');

        }

        $months = match ($duration) {

            '1_month' => 1,

            '3_months' => 3,

            '12_months' => 12,

            default => throw new \InvalidArgumentException('Durasi tidak dikenali.'),

        };

        $start = now();

        $end = now()->addMonths($months);

        $company->update([

            'subscription_plan' => $plan,

            'subscription_start' => $start,

            'subscription_end' => $end,

            'max_employee' => $plans[$plan]['max_employee'],

        ]);

        return $company->fresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Subscription (Revert to Free)
    |--------------------------------------------------------------------------
    */

    public function cancelSubscription(
        Company $company
    ): Company {

        $plans = config('plans');

        $company->update([

            'subscription_plan' => 'Free',

            'subscription_start' => now(),

            'subscription_end' => null,

            'max_employee' => $plans['Free']['max_employee'],

        ]);

        return $company->fresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Downgrade Expired Subscriptions (dipanggil dari scheduled command)
    |--------------------------------------------------------------------------
    */

    public function downgradeExpiredSubscriptions(): int
    {

        $plans = config('plans');

        $expired = Company::query()

            ->where('subscription_plan', '!=', 'Free')

            ->whereNotNull('subscription_end')

            ->whereDate('subscription_end', '<', today())

            ->get();

        foreach ($expired as $company) {

            $company->update([

                'subscription_plan' => 'Free',

                'max_employee' => $plans['Free']['max_employee'],

            ]);

        }

        return $expired->count();

    }

    /*
    |--------------------------------------------------------------------------
    | Private Methods
    |--------------------------------------------------------------------------
    */

    private function createCompany(
    array $data
    ): Company {

        /*
        |--------------------------------------------------------------------------
        | Upload Logo
        |--------------------------------------------------------------------------
        */

        $logo = $this->uploadLogo(
            $data['logo'] ?? null
        );

        /*
        |--------------------------------------------------------------------------
        | Create Company
        |--------------------------------------------------------------------------
        */

        return Company::create([

            'code' => strtoupper(trim($data['code'])),

            'name' => trim($data['name']),

            'email' => $data['email'] ?? null,

            'phone' => $data['phone'] ?? null,

            'website' => $data['website'] ?? null,

            'logo' => $logo,

            'address' => $data['address'] ?? null,

            'city' => $data['city'] ?? null,

            'province' => $data['province'] ?? null,

            'postal_code' => $data['postal_code'] ?? null,

            'timezone' => $data['timezone'] ?? 'Asia/Makassar',

            'subscription_plan' => 'Free',

            'subscription_start' => today(),

            'subscription_end' => today()->addYear(),

            'max_employee' => $data['max_employee'] ?? config('plans.Free.max_employee', 5),

            'is_active' => true,

        ]);
    }

   private function createHeadOffice(
Company $company,
array $data = []
): Office {

    return Office::create([

        'company_id' => $company->id,

        'code' => 'HO-' . $company->code,

        'name' => $company->name . ' - Head Office',

        'address' => $company->address ?? '-',

        'city' => $company->city,

        'province' => $company->province,

        'postal_code' => $company->postal_code,

        'timezone' => $company->timezone,

        /*
        |--------------------------------------------------------------------------
        | Coordinate
        |--------------------------------------------------------------------------
        */

        'latitude' => $data['latitude'] ?? 0,

        'longitude' => $data['longitude'] ?? 0,

        'radius' => 200,

        'polygon' => $this->decodePolygon($data['polygon'] ?? null),

        'is_active' => true,

        'is_head_office' => true,

    ]);

}

    private function seedMasterData(
    Company $company
    ): void {

        $departmentMap = DepartmentSeeder::seedForCompany(
            $company->id
        );

        PositionSeeder::seedForCompany(
            $company->id
        );

        TeamSeeder::seedForCompany(
            $company->id,
            $departmentMap
        );

    }

    private function createSuperAdmin(
    Company $company,
    array $data,
    string $password
    ): User {

        /*
        |--------------------------------------------------------------------------
        | User (Company Administrator)
        |--------------------------------------------------------------------------
        |
        | Sengaja TIDAK membuat record Employee / EmploymentHistory untuk admin.
        | Company Administrator murni akun pengelola (bisa dipakai bareng-bareng
        | oleh beberapa orang di company itu untuk konfigurasi sistem), bukan
        | karyawan yang di-absen / dihitung di Employee Management. Karyawan
        | sungguhan dibuat lewat menu Employee Management (EmployeeService).
        |
        */

        return $this->createUser(

            $company,

            $data,

            $password

        );

    }

    private function syncSuperAdmin(
    Company $company,
    array $data
    ): void {

        // Kalau tidak ada field admin_* yang dikirim (mis. request lain
        // yang reuse service ini), tidak usah ngapa-ngapain.
        if (
            !array_key_exists('admin_email', $data) &&
            !array_key_exists('admin_username', $data)
        ) {

            return;

        }

        $superAdmin = $company->users()

            ->whereHas(
                'role',
                fn ($q) => $q->where('code', 'SUPER_ADMIN')
            )

            ->first();

        if (!$superAdmin) {

            return;

        }

        $update = [];

        if (!empty($data['admin_email'])) {

            $update['email'] = $data['admin_email'];

        }

        if (!empty($data['admin_username'])) {

            $update['username'] = $this->generateUsername(
                $data['admin_username']
            );

        }

        if (!empty($update)) {

            $superAdmin->update($update);

        }

    }

    private function uploadLogo(
    ?UploadedFile $logo
    ): ?string {

        if (!$logo) {

            return null;

        }

        return app(SecureFileService::class)->store(

            $logo,

            'companies'

        );

    }

    private function deleteLogo(
    ?string $logo
    ): void {

        app(SecureFileService::class)->delete($logo);

    }

    private function generatePassword(): string
    {
        return strtoupper(
            Str::random(6)
        );
    }

   /*
    |--------------------------------------------------------------------------
    | Generate Username
    |--------------------------------------------------------------------------
    |
    | Username BUKAN lagi kredensial login (login pakai Email atau
    | NIP+Kode Company), jadi boleh sama/kembar antar user -- termasuk
    | dalam 1 company yang sama. Fungsi ini cuma membersihkan input jadi
    | format username yang rapi, TANPA cek keunikan/tambah suffix angka.
    |--------------------------------------------------------------------------
    */
   private function generateUsername(
    string $username
    ): string {

        return strtolower(

            preg_replace(

                '/[^a-zA-Z0-9]/',

                '',

                $username

            )

        );

    }
    private function createUser(
    Company $company,
    array $data,
    string $password
    ): User {

        $roleId = Role::query()

            ->where(

                'code',

                'SUPER_ADMIN'

            )

            ->value('id');

        return User::create([

            'company_id' => $company->id,

            'employee_id' => null,

            'role_id' => $roleId,

            'username' => $this->generateUsername(

                $data['admin_username']

            ),

            'email' => $data['admin_email'],

            'password' => Hash::make(

                $password

            ),

            /*
            |--------------------------------------------------------------------------
            | Wajib Ganti Password saat Login Pertama
            |--------------------------------------------------------------------------
            */

            'password_changed_at' => null,

            'is_active' => true,

        ]);

    }
    /*
    |--------------------------------------------------------------------------
    | Decode Polygon
    |--------------------------------------------------------------------------
    */

    private function decodePolygon(?string $polygon): ?array
    {

        if (empty($polygon)) {

            return null;

        }

        $decoded = json_decode($polygon, true);

        return is_array($decoded) && count($decoded) >= 3

            ? $decoded

            : null;

    }
}