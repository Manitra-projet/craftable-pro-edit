<?php

namespace CustomPackages\CustomApp\Queries\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortNullsLast implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        [$nulls, $direction] = $descending ? ['IS NULL', 'DESC'] : ['IS NOT NULL', 'ASC'];


        $query->orderByRaw("{$property} {$nulls}, {$property} {$direction}");
    }
}
