<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

final class CaseInsensitiveSearch
{
    /**
     * @param  list<string>  $columns
     */
    public static function contains(Builder $query, string $term, array $columns): Builder
    {
        return self::apply($query, $term, $columns, '%', '%');
    }

    public static function startsWith(Builder $query, string $term, string $column): Builder
    {
        return self::apply($query, $term, [$column], '', '%');
    }

    /**
     * @param  list<string>  $columns
     */
    private static function apply(Builder $query, string $term, array $columns, string $prefix, string $suffix): Builder
    {
        $term = trim($term);

        if ($term === '' || $columns === []) {
            return $query;
        }

        $pattern = $prefix.mb_strtolower($term).$suffix;
        $grammar = $query->getModel()->getConnection()->getQueryGrammar();

        return $query->where(function (Builder $nested) use ($columns, $pattern, $grammar): void {
            foreach ($columns as $index => $column) {
                $condition = 'LOWER('.$grammar->wrap($column).') LIKE ?';

                if ($index === 0) {
                    $nested->whereRaw($condition, [$pattern]);
                } else {
                    $nested->orWhereRaw($condition, [$pattern]);
                }
            }
        });
    }
}
