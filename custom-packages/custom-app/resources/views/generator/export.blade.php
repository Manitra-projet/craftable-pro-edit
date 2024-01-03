@php echo "<?php"
@endphp

namespace {{$exportNamespace}};

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use CustomPackages\CustomApp\Queries\Filters\FuzzyFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use {{$modelNamespace}}\{{$modelName}};

class {{$exportName}} implements FromCollection,WithHeadings
{
    protected mixed $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return QueryBuilder::for({{$modelName}}::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    {!! $showIndexColumns !!}
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts({!! $showIndexColumns !!})
            ->select([{!! $showIndexColumns !!}])
            ->get();
    }

    public function headings(): array
    {
        return [
    @foreach($exportColumns as $column)
        trans("custom-app.{{$column}}"),
    @endforeach
    ];
    }
}
