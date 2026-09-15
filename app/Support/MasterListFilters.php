<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class MasterListFilters
{
    public static function apply(Builder $query, Request $request): Builder
    {
        if ($request->filled('search')) {
            CaseInsensitiveSearch::contains($query, (string) $request->input('search'), ['code', 'name']);
        }

        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where(
                'is_active',
                filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN)
            );
        }

        return $query;
    }
}
