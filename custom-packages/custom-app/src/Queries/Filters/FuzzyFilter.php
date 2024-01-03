<?php

namespace CustomPackages\CustomApp\Queries\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * Inspired by: https://gist.github.com/freekmurze/9344ba437f14e4ba3c2dbd794e5883d9
 */
class FuzzyFilter implements Filter
{
    /** @var string[] */
    protected array $fields;

    /** @var string */
    protected string $likeOperator;

    public function __construct(string ...$fields)
    {
        $this->fields = $fields;
        $this->likeOperator = DB::connection()->getDriverName() == 'pgsql' ? 'ilike' : 'like';
    }

    public function __invoke(Builder $query, $values, string $property): Builder
    {
        $values = Arr::wrap($values);

        $query->where(function (Builder $query) use ($values) {
            $this
                ->addDirectFields($query, $values)
                ->addRelationShipFields($query, $values);
        });

        return $query;
    }

    public function addDirectFields(Builder $query, $values)
    {
        collect($this->fields)
            ->reject(fn (string $field) => Str::contains($field, '.'))
            ->each(function (string $field) use ($query, $values) {
                foreach ($values as $value) {
                    $query->orWhere($field, $this->likeOperator, "%{$value}%");
                }
            });

        return $this;
    }

    /*
     * Possibly wrong code, maybe this is correct one:
     *

 public function addRelationShipFields(Builder $query, $values)
{
    collect($this->fields)
        ->filter(fn (string $field) => Str::contains($field, '.'))
        ->each(function (string $field) use ($query, $values) {
            foreach ($values as $value) {
                [$relation, $column] = explode('.', $field);

                $query->orWhereHas($relation, function (Builder $query) use ($column, $value) {
                    $query->where($column, 'LIKE', "%{$value}%");
                });
            }
        });

    return $this;
}

     */
    public function addRelationShipFields(Builder $query, $values)
    {
        collect($this->fields)
            ->filter(fn (string $field) => Str::contains($field, '.'))
            ->each(function (string $field) use ($query, $values) {
                foreach ($values as $value) {
                    [$relation, $field] = explode('.', $field);

                    $query->orWhereHas($relation, function (Builder $query) use ($field, $value) {
                        $query->where($field, $this->likeOperator, "%{$value}%");
                    });
                }
            });

        return $this;
    }
}
