<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\Office;
use App\Models\Position;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;

use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PositionSeeder;
use Database\Seeders\TeamSeeder;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

                'company' => $company,

                'username' => $user->username,

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
            */

            $company->offices()

            ->where('is_head_office', true)

            ->update([

                'name' => $company->name . ' - Head Office',

                'address' => $company->address,

                'city' => $company->city,

                'province' => $company->province,

                'postal_code' => $company->postal_code,

                'timezone' => $company->timezone,

                'polygon' => $this->decodePolygon($data['polygon'] ?? null),

            ]);

            return $company->fresh();

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

            'max_employee' => $data['max_employee'] ?? 50,

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
        | Head Office
        |--------------------------------------------------------------------------
        */

        $office = Office::query()

            ->where('company_id', $company->id)

            ->where('is_head_office', true)

            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        $employee = $this->createEmployee(

            $company,

            $data

        );

        /*
        |--------------------------------------------------------------------------
        | Employment History
        |--------------------------------------------------------------------------
        */

        $this->createEmploymentHistory(

            $employee,

            $office

        );

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        return $this->createUser(

            $company,

            $employee,

            $data,

            $password

        );

    }

    private function uploadLogo(
    ?UploadedFile $logo
    ): ?string {

        if (!$logo) {

            return null;

        }

        return $logo->store(

            'companies',

            'public'

        );

    }

    private function deleteLogo(
    ?string $logo
    ): void {

        if (!$logo) {

            return;

        }

        if (

            Storage::disk('public')

                ->exists($logo)

        ) {

            Storage::disk('public')

                ->delete($logo);

        }

    }

    private function generatePassword(): string
    {
        return strtoupper(
            Str::random(6)
        );
    }

   private function generateUsername(
    string $username
    ): string {

        $username = strtolower(

            preg_replace(

                '/[^a-zA-Z0-9]/',

                '',

                $username

            )

        );

        $original = $username;

        $counter = 1;

        while (

            User::where(

                'username',

                $username

            )->exists()

        ) {

            $username =

                $original .

                $counter;

            $counter++;

        }

        return $username;

    }
    private function generateEmployeeNumber(
    Company $company
    ): string {

        $prefix = strtoupper($company->code);

        $last = Employee::query()

            ->where('company_id', $company->id)

            ->orderByDesc('id')

            ->first();

        if (!$last) {

            return $prefix . '-0001';

        }

        $number = (int) substr(

            $last->employee_number,

            -4

        );

        return sprintf(

            '%s-%04d',

            $prefix,

            $number + 1

        );

    }
    private function createEmployee(
    Company $company,
    array $data
    ): Employee {

        return Employee::create([

            'company_id' => $company->id,

            'employee_number' => $this->generateEmployeeNumber($company),

            'full_name' => $data['admin_name'],

            'email' => $data['admin_email'],

            'phone' => $data['admin_phone'] ?? null,

            'gender' => $data['admin_gender'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Dilengkapi Mandiri oleh Employee
            |--------------------------------------------------------------------------
            */

            'birth_date' => null,

            'photo' => null,

            'nik' => null,

            'is_active' => true,

        ]);

    }
    private function createEmploymentHistory(
    Employee $employee,
    Office $office
    ): void {

        EmploymentHistory::create([

            'employee_id' => $employee->id,

            'department_id' => Department::where(

                'company_id',

                $employee->company_id

            )->where(

                'code',

                'ADM'

            )->value('id'),

            'position_id' => Position::where(

                'company_id',

                $employee->company_id

            )->where(

                'code',

                'ADM'

            )->value('id'),

            'team_id' => null,

            'office_id' => $office->id,

            'shift_id' => Shift::where(

                'code',

                'PAGI'

            )->value('id'),

            'supervisor_id' => null,

            'employment_type' => 'Permanent',

            'employment_status' => 'Active',

            'start_date' => today(),

            'is_current' => true,

        ]);

    }
    private function createUser(
    Company $company,
    Employee $employee,
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

            'employee_id' => $employee->id,

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