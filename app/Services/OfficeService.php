<?php

namespace App\Services;

use App\Models\Office;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OfficeService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Office List
    |--------------------------------------------------------------------------
    */

    public function getOffices(
        array $filters = []
    ): LengthAwarePaginator {

        $query = Office::query()

            ->forCurrentCompany()

            ->withCount([

                'employees',

                'attendances',

                'assignments',

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
                        'province',
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
        | Province
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['province'])) {

            $query->where(
                'province',
                $filters['province']
            );

        }

        /*
        |--------------------------------------------------------------------------
        | City
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['city'])) {

            $query->where(
                'city',
                $filters['city']
            );

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
        | Pagination
        |--------------------------------------------------------------------------
        */

        return $query

            ->orderByDesc('is_head_office')

            ->orderBy('name')

            ->paginate(

                $filters['per_page'] ?? 10

            )

            ->withQueryString();

    }

    /*
    |--------------------------------------------------------------------------
    | Find Office
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): Office {

        return Office::query()

            ->forCurrentCompany()

            ->with([
                'company' => fn ($query) => $query->withCount('employees'),
            ])

            ->withCount([

                'employees',

                'attendances',

                'assignments',

            ])

            ->findOrFail($id);

    }

    /*
    |--------------------------------------------------------------------------
    | Update Office
    |--------------------------------------------------------------------------
    */

    public function update(
        Office $office,
        array $data
    ): Office {

        $this->authorizeCompany($office);

        return DB::transaction(function () use ($office, $data) {

            /*
            |--------------------------------------------------------------------------
            | Default Value
            |--------------------------------------------------------------------------
            */

            $data['is_active'] = (bool) ($data['is_active'] ?? false);

            $data['is_head_office'] = (bool) ($data['is_head_office'] ?? false);

            $data['polygon'] = $this->decodePolygon(
                $data['polygon'] ?? null
            );

            /*
            |--------------------------------------------------------------------------
            | Only One Head Office
            |--------------------------------------------------------------------------
            */

            if ($data['is_head_office']) {

                Office::query()

                    ->forCurrentCompany()

                    ->whereKeyNot($office->id)

                    ->where('is_head_office', true)

                    ->update([

                        'is_head_office' => false,

                    ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            $office->update($data);

            return $office->fresh();

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Province List
    |--------------------------------------------------------------------------
    */

    public function provinces()
    {
        return Office::query()

            ->forCurrentCompany()

            ->whereNotNull('province')

            ->where('province', '<>', '')

            ->distinct()

            ->orderBy('province')

            ->pluck('province');
    }

    /*
    |--------------------------------------------------------------------------
    | City List
    |--------------------------------------------------------------------------
    */

    public function cities()
    {
        return Office::query()

            ->forCurrentCompany()

            ->whereNotNull('city')

            ->where('city', '<>', '')

            ->distinct()

            ->orderBy('city')

            ->pluck('city');
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