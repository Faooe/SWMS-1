<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Office;
use App\Models\Position;
use App\Models\Shift;
use App\Models\Team;
use Illuminate\Support\Collection;

class MasterService
{
    /**
     * Departments
     */
    public function departments(): Collection
    {
        return Department::query()
            ->forCurrentCompany()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Positions
     */
    public function positions(): Collection
    {
        return Position::query()
            ->forCurrentCompany()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Teams
     */
    public function teams(): Collection
    {
        return Team::query()
            ->forCurrentCompany()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Offices
     */
    public function offices(): Collection
    {
        return Office::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Shifts
     */
    public function shifts(): Collection
    {
        return Shift::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}