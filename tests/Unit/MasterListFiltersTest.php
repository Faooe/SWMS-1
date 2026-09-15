<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Support\CaseInsensitiveSearch;
use App\Support\MasterListFilters;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MasterListFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->boolean('is_active');
            $table->softDeletes();
        });
    }

    public function test_search_finds_a_mixed_case_department_name_and_respects_active_status(): void
    {
        DB::table('departments')->insert([
            ['code' => 'FIN', 'name' => 'Finance', 'is_active' => true],
            ['code' => 'FIN-OLD', 'name' => 'Finance Lama', 'is_active' => false],
            ['code' => 'HR', 'name' => 'Human Resources', 'is_active' => true],
        ]);

        $request = Request::create('/', 'GET', [
            'search' => '  fInAnCe  ',
            'is_active' => 'true',
        ]);

        $result = MasterListFilters::apply(Department::query(), $request)
            ->pluck('code')
            ->all();

        $this->assertSame(['FIN'], $result);
    }

    public function test_prefix_search_is_case_insensitive_without_matching_the_middle(): void
    {
        DB::table('departments')->insert([
            ['code' => 'FIN-0001', 'name' => 'Finance', 'is_active' => true],
            ['code' => 'X-FIN-0002', 'name' => 'Legacy', 'is_active' => true],
        ]);

        $result = CaseInsensitiveSearch::startsWith(Department::query(), 'fin-', 'code')
            ->pluck('code')
            ->all();

        $this->assertSame(['FIN-0001'], $result);
    }
}
