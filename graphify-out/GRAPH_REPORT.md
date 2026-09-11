# Graph Report - SWMS_Backup3  (2026-09-11)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 2301 nodes · 5452 edges · 326 communities (75 shown, 61 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 137 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ae5cbd9b`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Assignment
- Illuminate\Http\Request
- Closure
- MultiSheetXlsxWriter
- Illuminate\Foundation\Http\FormRequest
- package.json
- TestCase
- Role
- Position
- Illuminate\Http\JsonResponse
- Illuminate\Notifications\Notification
- WorkCalendarService
- Employee
- Illuminate\Support\Collection
- Illuminate\Database\Eloquent\Relations\BelongsTo
- ResponseHelper
- AssignmentEmployee
- Company
- User
- Illuminate\Database\Eloquent\Model
- AttendanceManagementService
- Illuminate\Database\Eloquent\Relations\HasMany
- Attendance
- Office
- Team
- Illuminate\Database\Eloquent\Builder
- Livewire\Component
- SubscriptionPayment
- .response
- Department
- AssignmentLog
- AttendanceService
- env
- CompanyService.php
- Illuminate\Support\Facades\Auth
- static
- EmployeeService
- EmployeePerformanceService
- .view
- AttendanceCheckoutCorrection
- api.php
- LeaveRequestService
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Console\Command
- web.php
- LeaveRequest
- AttendanceLocationService
- EmployeeController
- EmployeePerformanceExport
- SubscriptionPeriodCalculator
- Illuminate\Database\Schema\Blueprint
- Illuminate\Database\Migrations\Migration
- Illuminate\Support\Facades\Schema
- AppServiceProvider.php
- CompanyResource
- StrongPasswordGenerator
- employee-manager.blade.php
- V1/Employee/LeaveRequestController.php
- TeamManager
- ImportManager
- LeaveRequestReviewed
- Employee/EmployeeController.php
- DashboardService
- MidtransService
- StoreLeaveRequestRequest
- FcmChannel
- AbsentAttendanceService
- assignments/show.blade.php
- AssignmentNotWorked
- PositionController
- StoredFile
- Manager
- CompanyPolicy
- composer.json
- require
- scripts
- EmployeePerformanceController
- .build
- BaseService
- EmployeeImportService
- .run
- AttendanceExport
- CompanyManager
- FirebaseAuthService
- leave/manager.blade.php
- config
- require-dev
- position-manager.blade.php
- team-manager.blade.php
- AttendanceManagementController
- LeaveQuotaController.php
- ChangePasswordRequest
- LeaveRequestController
- StoreCompanyRequest
- LeaveManager
- AssignmentAssigned
- AssignmentResponseUpdated
- EmployeeMarkedAbsent
- attendance/show.blade.php
- psr-4
- office/edit.blade.php
- CronController.php
- UpdateEmployeeRequest
- ChangePasswordRequest
- LoginRequest
- app.blade.php
- logging.php
- sanctum.php
- employee/dashboard/index.blade.php
- CompanyPremiumLifecycleTest
- ProfileController
- ProfileController
- SubscriptionController
- UpdateProfileRequest
- StoreEmployeeRequest
- company-manager.blade.php
- import-manager.blade.php
- employee/attendance/index.blade.php
- attendance/manager.blade.php
- employee/manager.blade.php
- team/manager.blade.php
- autoload-dev
- extra
- department/manager.blade.php
- position/manager.blade.php
- employee.assignments.partials.card
- employee.assignments.partials.completion-form
- assignment/show.blade.php
- master-filter.blade.php
- pagination.blade.php
- edit-form.blade.php
- leave-manager.blade.php
- company/create.blade.php
- company/edit.blade.php
- livewire/tailwind.blade.php
- pagination/tailwind.blade.php

## God Nodes (most connected - your core abstractions)
1. `Employee` - 139 edges
2. `ResponseHelper` - 124 edges
3. `User` - 124 edges
4. `Assignment` - 107 edges
5. `Controller` - 106 edges
6. `Company` - 95 edges
7. `Attendance` - 90 edges
8. `AssignmentEmployee` - 75 edges
9. `Office` - 59 edges
10. `Department` - 57 edges

## Surprising Connections (you probably didn't know these)
- `AssignmentController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Web/AssignmentController.php → app/Http/Controllers/Controller.php
- `AssignmentController` --references--> `AssignmentService`  [EXTRACTED]
  app/Http/Controllers/Api/V1/Assignment/AssignmentController.php → app/Services/AssignmentService.php
- `AssignmentService` --inherits--> `BaseService`  [EXTRACTED]
  app/Services/AssignmentService.php → app/Services/BaseService.php
- `AssignmentController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/V1/Assignment/AssignmentController.php → app/Http/Controllers/Controller.php
- `AttendanceController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/V1/Attendance/AttendanceController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (326 total, 61 thin omitted)

### Community 0 - "Assignment"
Cohesion: 0.06
Nodes (7): AssignmentController, RejectCompletionRequest, UpdateAssignmentRequest, EmployeeManager, Assignment, AssignmentService, AttendanceCheckoutCorrectionService

### Community 1 - "Illuminate\Http\Request"
Cohesion: 0.06
Nodes (12): secure_file_url(), AssignmentSettingsController, Controller, FirebaseLoginController, LoginController, AssignmentController, AttendanceController, AuthService (+4 more)

### Community 2 - "Closure"
Cohesion: 0.07
Nodes (24): CheckCompanyActive, EmployeeMiddleware, PlatformMiddleware, RequestContext, RoleMiddleware, SecurityHeaders, SuperAdminMiddleware, Badge (+16 more)

### Community 3 - "MultiSheetXlsxWriter"
Cohesion: 0.07
Nodes (8): CompanyHrRecapController, CompanyHrRecapController, MultiSheetXlsxWriter, self, self, XlsxWriter, Symfony\Component\HttpFoundation\StreamedResponse, ZipArchive

### Community 4 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.05
Nodes (11): AssignmentLocationRequest, CompleteAssignmentRequest, StoreAssignmentRequest, CheckInRequest, CheckOutRequest, EmployeeLoginRequest, OfficeRequest, UpdateCompanyRequest (+3 more)

### Community 5 - "package.json"
Cohesion: 0.06
Nodes (42): dependencies, alpinejs, browser-image-compression, chart.js, lottie-web, lucide, devDependencies, concurrently (+34 more)

### Community 6 - "TestCase"
Cohesion: 0.07
Nodes (11): LengthAwarePaginator, EmployeeAssignmentOrdering, Illuminate\Foundation\Testing\TestCase, CompanyHrRecapRoutesTest, ExampleTest, PhaseOneApiHardeningTest, PhaseTwoSubscriptionRoutesTest, TestCase (+3 more)

### Community 7 - "Role"
Cohesion: 0.07
Nodes (13): Permission, Role, DatabaseSeeder, DepartmentSeeder, EmployeeSeeder, EmploymentHistorySeeder, OfficeSeeder, PermissionSeeder (+5 more)

### Community 8 - "Position"
Cohesion: 0.10
Nodes (5): PositionController, Manager, PositionManager, Position, PositionSeeder

### Community 9 - "Illuminate\Http\JsonResponse"
Cohesion: 0.12
Nodes (5): DepartmentController, OfficeController, TeamController, NotificationController, Illuminate\Http\JsonResponse

### Community 10 - "Illuminate\Notifications\Notification"
Cohesion: 0.09
Nodes (7): AssignmentReviewUpdated, EmployeeAttendanceAbsent, LeaveRequestSubmitted, SubscriptionChanged, SubscriptionExpiryReminder, Illuminate\Bus\Queueable, Illuminate\Notifications\Notification

### Community 11 - "WorkCalendarService"
Cohesion: 0.13
Nodes (8): WorkCalendarController, WorkCalendarController, CompanyHoliday, WorkCalendarService, Carbon\CarbonInterface, Illuminate\Http\RedirectResponse, Illuminate\View\View, CompanyHrRecapRangeTest

### Community 12 - "Employee"
Cohesion: 0.12
Nodes (3): Employee, AttendanceService, Illuminate\Database\Eloquent\Relations\HasOne

### Community 13 - "Illuminate\Support\Collection"
Cohesion: 0.14
Nodes (6): CompanyHrRecapService, Carbon, MasterService, Carbon\Carbon, Illuminate\Pagination\LengthAwarePaginator, Illuminate\Support\Collection

### Community 14 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.08
Nodes (4): AssignmentAttachment, CompanyWorkSchedule, EmploymentHistory, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 15 - "ResponseHelper"
Cohesion: 0.17
Nodes (4): ResponseHelper, AssignmentController, AssignmentController, AssignmentResource

### Community 18 - "User"
Cohesion: 0.11
Nodes (4): User, ProfileService, Illuminate\Support\Facades\Hash, Laravel\Sanctum\PersonalAccessToken

### Community 19 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.13
Nodes (9): dashboard, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsToMany, Illuminate\Database\Eloquent\Relations\HasManyThrough, Illuminate\Database\Eloquent\SoftDeletes, Illuminate\Support\Str, Pdo\Mysql (+1 more)

### Community 20 - "AttendanceManagementService"
Cohesion: 0.12
Nodes (5): AttendanceController, App\Repositories\Interfaces\RoleRepositoryInterface, AttendanceManagementService, RoleService, Illuminate\Database\Eloquent\Collection

### Community 21 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.08
Nodes (3): Shift, ShiftSeeder, Illuminate\Database\Eloquent\Relations\HasMany

### Community 22 - "Attendance"
Cohesion: 0.10
Nodes (4): DetachAdminEmployees, DashboardController, Attendance, DashboardService

### Community 23 - "Office"
Cohesion: 0.12
Nodes (3): OfficeController, Office, OfficeService

### Community 24 - "Team"
Cohesion: 0.12
Nodes (4): TeamController, Manager, Team, TeamSeeder

### Community 25 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.07
Nodes (5): Illuminate\Database\Eloquent\Builder, Illuminate\Database\Eloquent\Relations\Pivot, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens

### Community 26 - "Livewire\Component"
Cohesion: 0.14
Nodes (7): Manager, Dashboard, Manager, Illuminate\Support\Facades\Gate, Livewire\Attributes\Url, Livewire\Component, Livewire\WithPagination

### Community 27 - "SubscriptionPayment"
Cohesion: 0.12
Nodes (4): PremiumController, SubscriptionController, SubscriptionPayment, SubscriptionPaymentData

### Community 28 - ".response"
Cohesion: 0.18
Nodes (3): AttendanceController, CronController, AttendanceResource

### Community 29 - "Department"
Cohesion: 0.16
Nodes (4): DepartmentController, EditForm, Manager, Department

### Community 31 - "AttendanceService"
Cohesion: 0.22
Nodes (4): DashboardController, DashboardController, AttendanceService, EmployeeDashboardService

### Community 32 - "env"
Cohesion: 0.09
Nodes (22): runtime, crons, env, APP_CONFIG_CACHE, APP_DEBUG, APP_ENV, APP_EVENTS_CACHE, APP_PACKAGES_CACHE (+14 more)

### Community 33 - "CompanyService.php"
Cohesion: 0.17
Nodes (9): SecureFileController, SecureFileService, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Http\Response, Illuminate\Http\UploadedFile, Illuminate\Support\Facades\DB, Illuminate\Support\Facades\Notification, Illuminate\Support\Facades\URL (+1 more)

### Community 34 - "Illuminate\Support\Facades\Auth"
Cohesion: 0.16
Nodes (6): StoreEmployeeRequest, Illuminate\Database\UniqueConstraintViolationException, Illuminate\Support\Facades\Auth, Illuminate\Support\Facades\Log, Illuminate\Validation\Rule, Illuminate\Validation\Rules\Password

### Community 35 - "static"
Cohesion: 0.10
Nodes (5): EmployeeFactory, EmploymentHistoryFactory, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 39 - "AttendanceCheckoutCorrection"
Cohesion: 0.13
Nodes (3): AttendanceCheckoutCorrection, CheckoutCorrectionRequested, CheckoutCorrectionReviewed

### Community 40 - "api.php"
Cohesion: 0.19
Nodes (4): AuthController, FirebaseAuthController, ProfileController, UserResource

### Community 41 - "LeaveRequestService"
Cohesion: 0.18
Nodes (3): LeaveQuota, LeaveQuotaService, LeaveRequestService

### Community 42 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.19
Nodes (4): MasterController, MasterResource, RoleResource, Illuminate\Http\Resources\Json\JsonResource

### Community 43 - "Illuminate\Console\Command"
Cohesion: 0.17
Nodes (6): ActivateScheduledAssignments, AutoRejectExpiredLeaveRequests, DowngradeExpiredSubscriptions, MarkAbsentEmployees, SendSubscriptionExpiryReminders, Illuminate\Console\Command

### Community 44 - "web.php"
Cohesion: 0.14
Nodes (4): PremiumController, ProfileController, AssignmentSettingsController, Illuminate\Support\Facades\Route

### Community 46 - "AttendanceLocationService"
Cohesion: 0.18
Nodes (3): AttendanceLocationService, HaversineService, PolygonService

### Community 49 - "SubscriptionPeriodCalculator"
Cohesion: 0.23
Nodes (5): SubscriptionPeriodCalculator, Carbon\CarbonImmutable, PHPUnit\Framework\TestCase, ExampleTest, SubscriptionPeriodCalculatorTest

### Community 53 - "AppServiceProvider.php"
Cohesion: 0.21
Nodes (6): CustomPostgresConnector, AppServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Database\Connectors\PostgresConnector, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider

### Community 54 - "CompanyResource"
Cohesion: 0.30
Nodes (3): CompanyController, DashboardController, CompanyResource

### Community 55 - "StrongPasswordGenerator"
Cohesion: 0.21
Nodes (3): StrongPasswordGenerator, InvalidArgumentException, StrongPasswordGeneratorTest

### Community 56 - "employee-manager.blade.php"
Cohesion: 0.18
Nodes (10): addEmployee({{ $employee->id }}), approveCheckoutCorrection({{ $correction->id }}), approveCompletion({{ $employee->id }}), closePicker, closeReject, openPicker, openReject({{ $employee->id }}), rejectCheckoutCorrection({{ $correction->id }}) (+2 more)

### Community 57 - "V1/Employee/LeaveRequestController.php"
Cohesion: 0.31
Nodes (3): LeaveRequestController, LeaveRequestController, LeaveRequestResource

### Community 59 - "ImportManager"
Cohesion: 0.20
Nodes (3): ImportManager, Livewire\Attributes\Validate, Livewire\WithFileUploads

### Community 63 - "MidtransService"
Cohesion: 0.36
Nodes (3): MidtransService, Illuminate\Support\Facades\Http, RuntimeException

### Community 65 - "FcmChannel"
Cohesion: 0.29
Nodes (7): FcmChannel, Kreait\Firebase\Contract\Messaging, Kreait\Firebase\Exception\Messaging\InvalidMessage, Kreait\Firebase\Exception\Messaging\NotFound, Kreait\Firebase\Messaging\CloudMessage, Kreait\Firebase\Messaging\Notification, Throwable

### Community 67 - "assignments/show.blade.php"
Cohesion: 0.20
Nodes (9): employee.assignments.partials.actions, employee.assignments.partials.daily-attendance, employee.assignments.partials.description, employee.assignments.partials.header, employee.assignments.partials.location, employee.assignments.partials.team, employee.assignments.partials.timeline, employee.assignments.partials.work-session (+1 more)

### Community 73 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 74 - "require"
Cohesion: 0.22
Nodes (9): require, barryvdh/laravel-dompdf, doctrine/dbal, kreait/firebase-php, laravel/framework, laravel/sanctum, laravel/tinker, livewire/livewire (+1 more)

### Community 75 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 83 - "FirebaseAuthService"
Cohesion: 0.33
Nodes (4): FirebaseAuthService, Kreait\Firebase\Auth, Kreait\Firebase\Exception\Auth\FailedToVerifyToken, Kreait\Firebase\Factory

### Community 84 - "leave/manager.blade.php"
Cohesion: 0.29
Nodes (6): approve({{ $leave->id }}), cancelReject, confirmReject, resetFilters, showAll, startReject({{ $leave->id }})

### Community 85 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 86 - "require-dev"
Cohesion: 0.29
Nodes (7): require-dev, fakerphp/faker, laravel/pail, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 87 - "position-manager.blade.php"
Cohesion: 0.29
Nodes (6): createPosition, editPosition({{ $position->id }}), cancelForm, deletePosition({{ $position->id }}), save, toggleStatus({{ $position->id }})

### Community 88 - "team-manager.blade.php"
Cohesion: 0.29
Nodes (6): createTeam, editTeam({{ $team->id }}), cancelForm, deleteTeam({{ $team->id }}), save, toggleStatus({{ $team->id }})

### Community 98 - "attendance/show.blade.php"
Cohesion: 0.33
Nodes (5): attendance.partials.attendance-card, attendance.partials.employee-card, attendance.partials.gps-card, attendance.partials.photos-card, attendance.partials.timeline-card

### Community 99 - "psr-4"
Cohesion: 0.33
Nodes (6): autoload, files, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "office/edit.blade.php"
Cohesion: 0.33
Nodes (5): office.partials.action, office.partials.company-info, office.partials.form, office.partials.map, office.partials.status

### Community 101 - "CronController.php"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\Schedule

### Community 105 - "app.blade.php"
Cohesion: 0.40
Nodes (4): components.subscription-badge, partials.loading-overlay, partials.navbar, partials.sidebar

### Community 106 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 107 - "sanctum.php"
Cohesion: 0.40
Nodes (4): Illuminate\Cookie\Middleware\EncryptCookies, Illuminate\Foundation\Http\Middleware\ValidateCsrfToken, Laravel\Sanctum\Http\Middleware\AuthenticateSession, Laravel\Sanctum\Sanctum

### Community 108 - "employee/dashboard/index.blade.php"
Cohesion: 0.40
Nodes (4): employee.dashboard.partials.activities, employee.dashboard.partials.greeting, employee.dashboard.partials.statistics, employee.dashboard.partials.today-overview

### Community 115 - "company-manager.blade.php"
Cohesion: 0.50
Nodes (3): deleteCompany({{ $company->id }}), resetFilters, toggleStatus({{ $company->id }})

### Community 116 - "import-manager.blade.php"
Cohesion: 0.50
Nodes (3): downloadResult, downloadTemplate, reset_

### Community 117 - "employee/attendance/index.blade.php"
Cohesion: 0.50
Nodes (3): employee.attendance.partials.assignment-card, employee.attendance.partials.office-card, employee.attendance.partials.today-status

### Community 118 - "attendance/manager.blade.php"
Cohesion: 0.50
Nodes (3): resetFilters, $set(, showAllDates

### Community 119 - "employee/manager.blade.php"
Cohesion: 0.50
Nodes (3): resetFilters, sortBy(, toggleStatus({{ $employee->id }})

### Community 120 - "team/manager.blade.php"
Cohesion: 0.50
Nodes (3): deleteTeam({{ $team->id }}), resetFilters, toggleStatus({{ $team->id }})

### Community 121 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 122 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **168 isolated node(s):** `office.partials.action`, `office.partials.company-info`, `office.partials.form`, `office.partials.map`, `office.partials.status` (+163 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 727 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **61 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Employee` connect `Employee` to `Assignment`, `Illuminate\Http\Request`, `Role`, `Illuminate\Support\Collection`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ResponseHelper`, `AssignmentEmployee`, `Company`, `User`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Attendance`, `Office`, `Team`, `Illuminate\Database\Eloquent\Builder`, `Livewire\Component`, `.response`, `AttendanceService`, `CompanyService.php`, `Illuminate\Support\Facades\Auth`, `static`, `EmployeeService`, `EmployeePerformanceService`, `LeaveRequestService`, `LeaveRequest`, `EmployeeController`, `EmployeePerformanceExport`, `Employee/EmployeeController.php`, `AbsentAttendanceService`, `EmployeePerformanceController`, `.build`, `.run`, `LeaveQuotaController.php`, `UpdateEmployeeRequest`?**
  _High betweenness centrality (0.105) - this node is a cross-community bridge._
- **Why does `Assignment` connect `Assignment` to `TestCase`, `Employee`, `Illuminate\Support\Collection`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ResponseHelper`, `User`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Attendance`, `Illuminate\Database\Eloquent\Builder`, `AssignmentLog`, `AttendanceService`, `CompanyService.php`, `Illuminate\Support\Facades\Auth`, `static`, `AttendanceLocationService`, `AbsentAttendanceService`, `.build`, `.run`?**
  _High betweenness centrality (0.091) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Illuminate\Http\Request`, `Closure`, `TestCase`, `Role`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ResponseHelper`, `Company`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Attendance`, `Illuminate\Database\Eloquent\Builder`, `.response`, `AssignmentLog`, `AttendanceService`, `CompanyService.php`, `Illuminate\Support\Facades\Auth`, `static`, `EmployeeService`, `.view`, `api.php`, `LeaveRequestService`, `LeaveRequestReviewed`, `AbsentAttendanceService`, `StoredFile`, `CompanyPolicy`, `.run`, `ProfileController`, `ProfileController`?**
  _High betweenness centrality (0.090) - this node is a cross-community bridge._
- **What connects `office.partials.action`, `office.partials.company-info`, `office.partials.form` to the rest of the system?**
  _168 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Assignment` be split into smaller, more focused modules?**
  _Cohesion score 0.05582603050957481 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.06013986013986014 - nodes in this community are weakly interconnected._
- **Should `Closure` be split into smaller, more focused modules?**
  _Cohesion score 0.07058001397624039 - nodes in this community are weakly interconnected._