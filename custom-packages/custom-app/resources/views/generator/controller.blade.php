@php echo "<?php"
@endphp

namespace {{$controllerNamespace}};

use {{$modelNamespace}}\{{$modelName}};
@foreach($relations->unique('model') as $relation)
@if($relation['model'] !== $modelName)
use {{ $relation['namespace'] }};
@endif
@endforeach
use App\Http\Controllers\Controller;
use {{ $indexRequestNamespace }};
use {{ $createRequestNamespace }};
use {{ $storeRequestNamespace }};
use {{ $editRequestNamespace }};
use {{ $updateRequestNamespace }};
use {{ $destroyRequestNamespace }};
use {{ $bulkDestroyRequestNamespace }};
use CustomPackages\CustomApp\Queries\Filters\FuzzyFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
@if ($export)
use Maatwebsite\Excel\Facades\Excel;
use {{ $exportNamespace }}\{{ $exportName }};
use Symfony\Component\HttpFoundation\BinaryFileResponse;
@endif

class {{$controllerName}} extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index({{$indexRequest}} $request): Response | JsonResponse
    {
        ${{$modelNamePluralLowerCase}}Query = QueryBuilder::for({{$modelName}}::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    {!! $showIndexColumns !!}
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts({!! $showIndexColumns !!});

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json(${{$modelNamePluralLowerCase}}Query->select(['id'])->pluck('id'));
        }

        ${{$modelNamePluralLowerCase}} = ${{$modelNamePluralLowerCase}}Query
@if(!$relations?->isEmpty())
            ->with({!!$relations->unique('name')->map(fn ($relation) => "'{$relation['name']}'")->implode(", ")!!})
@endif
            ->select({!! $showIndexColumns !!})
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('{{$modelNamePluralLowerCase}}_url', $request->fullUrl());

        return Inertia::render('{{$modelName}}/Index', [
            '{{$modelNamePluralLowerCase}}' => ${{$modelNamePluralLowerCase}},
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create({{$createRequest}} $request): Response
    {
        return Inertia::render('{{$modelName}}/Create', [
            {!! $modelRelationsGetQueries !!}
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store({{$storeRequest}} $request): RedirectResponse
    {
        ${{$modelNameLowerCase}} = {{$modelName}}::create($request->validated());

@if($modelRelationsSyncQueries)
        {!! $modelRelationsSyncQueries !!}

@endif
        return redirect()->route('{{$modelRoute}}.index')->with(['message' => ___('custom-app', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit({{$editRequest}} $request, {{$modelName}} ${{$modelNameLowerCase}}): Response
    {
@if($hasMediaCollections)
        ${{$modelNameLowerCase}}->load('media');

@endif
@if($modelRelationsLoad)
        {!! $modelRelationsLoad !!}

@endif
        return Inertia::render('{{$modelName}}/Edit', [
            '{{$modelNameLowerCase}}' => ${{$modelNameLowerCase}},
            {!! $modelRelationsGetQueries !!}
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update({{$updateRequest}} $request, {{$modelName}} ${{$modelNameLowerCase}}): RedirectResponse
    {
        ${{$modelNameLowerCase}}->update($request->validated());

@if($modelRelationsSyncQueries)
        {!! $modelRelationsSyncQueries !!}

@endif
        if (session('{{$modelNamePluralLowerCase}}_url')) {
            return redirect(session('{{$modelNamePluralLowerCase}}_url'))->with(['message' => ___('custom-app', 'Operation successful')]);
        }

        return redirect()->route('{{$modelRoute}}.index')->with(['message' => ___('custom-app', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy({{$destroyRequest}} $request, {{$modelName}} ${{$modelNameLowerCase}}): RedirectResponse
    {
@if($modelRelationsDetachQueries)
        {!! $modelRelationsDetachQueries !!}
@endif
        ${{$modelNameLowerCase}}->delete();

        return redirect()->back()->with(['message' => ___('custom-app', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy({{$bulkDestroyRequest}} $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    {{$modelName}}::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                {{$modelName}}::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('custom-app', 'Operation successful')]);
    }

@if($export)
    /**
     * Export
     */
    public function export({{$indexRequest}} $request): BinaryFileResponse
    {
        return Excel::download(new {{$exportName}}($request->all()), '{{$exportFileName}}-'.now()->format("dmYHi").'.xlsx');
    }
@endif
}
