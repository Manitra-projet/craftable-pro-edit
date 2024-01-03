<?php

namespace CustomPackages\CustomApp\Http\Controllers\Translations;

use CustomPackages\CustomApp\Http\Requests\Translation\ExportTranslations;
use CustomPackages\CustomApp\Http\Requests\Translation\ImportTranslation;
use CustomPackages\CustomApp\Http\Requests\Translation\IndexTranslation;
use CustomPackages\CustomApp\Http\Requests\Translation\PublishTranslations;
use CustomPackages\CustomApp\Http\Requests\Translation\RescanTranslations;
use CustomPackages\CustomApp\Http\Requests\Translation\UpdateTranslation;
use CustomPackages\CustomApp\Queries\Filters\FuzzyFilter;
use CustomPackages\CustomApp\Translations\Export\TranslationsExport;
use CustomPackages\CustomApp\Translations\LanguageLine;
use CustomPackages\CustomApp\Translations\Service\TranslationService;
use CustomPackages\CustomApp\Translations\TranslationsListingDataProcessor;
use CustomPackages\CustomApp\Translations\TranslationsProcessor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TranslationsController extends Controller
{
    protected $translationService;

    public function __construct(
        TranslationService $translationService
    ) {
        $this->translationService = $translationService;
    }

    public function index(IndexTranslation $request, TranslationsListingDataProcessor $translationsListingDataProcessor)
    {
        $data = QueryBuilder::for(LanguageLine::class)
            ->allowedFilters(
                [
                    AllowedFilter::exact('group'),
                    AllowedFilter::custom('search', new FuzzyFilter(
                        'group',
                        'key',
                        'text'
                    )),
                ]
            )
            ->defaultSort('id')
            ->allowedSorts(['id', 'group', 'key', 'text', 'created_at'])
            ->select(['id', 'group', 'key', 'text', 'created_at'])
            ->paginate(request()->get('per_page'))->withQueryString();

        return Inertia::render('Translations/Index', $translationsListingDataProcessor->getProcessedData($data));
    }

    public function update(UpdateTranslation $request, LanguageLine $translation)
    {
        $translation->update($request->validated());

        return redirect()->back()->with(['message' => ___('custom-app', 'Translations successfully updated')]);
    }

    public function rescan(RescanTranslations $request, TranslationsProcessor $translationsProcessor)
    {
        $translationsProcessor->scanTranslations();

        return redirect()->back()->with(['message' => ___('custom-app', 'Translations successfully re-scanned')]);
    }

    public function publish(PublishTranslations $request, TranslationsProcessor $translationsProcessor)
    {
        $translationsProcessor->publishTranslations();

        return redirect()->back()->with(['message' => ___('custom-app', 'Translations successfully published')]);
    }

    public function export(ExportTranslations $request)
    {
        $currentTime = Carbon::now()->toDateTimeString();
        $nameOfExportedFile = 'translations' . $currentTime . '.xlsx';

        return Excel::download(new TranslationsExport($request), $nameOfExportedFile);
    }

    /**
     * @param ImportTranslation $request
     * @return array|JsonResponse|mixed
     */
    public function import(ImportTranslation $request)
    {
        if ($request->hasFile('fileImport')) {
            $chosenLanguage = $request->getChosenLanguage();

            try {
                $collectionFromImportedFile = $this->translationService->getCollectionFromImportedFile($request->file('fileImport'), $chosenLanguage);
            } catch (Exception $e) {
                return response()->json($e->getMessage(), 409);
            }

            $existingTranslations = $this->translationService->getAllTranslationsForGivenLang($chosenLanguage);

            if ($request->input('onlyMissing') === 'true') {
                $filteredCollection = $this->translationService->getFilteredExistingTranslations($collectionFromImportedFile, $existingTranslations);
                $this->translationService->saveCollection($filteredCollection, $chosenLanguage);

                return ['numberOfImportedTranslations' => count($filteredCollection), 'numberOfUpdatedTranslations' => 0];
            } else {
                $collectionWithConflicts = $this->translationService->getCollectionWithConflicts($collectionFromImportedFile, $existingTranslations, $chosenLanguage);
                $numberOfConflicts = $this->translationService->getNumberOfConflicts($collectionWithConflicts);

                if ($numberOfConflicts === 0) {
                    return $this->translationService->checkAndUpdateTranslations($chosenLanguage, $existingTranslations, $collectionWithConflicts);
                }

                return $collectionWithConflicts;
            }
        }

        return response()->json(___('custom-app', 'No file imported'), 409);
    }

    public function importResolvedConflicts(UpdateTranslation $request)
    {
        $resolvedConflicts = collect($request->getResolvedConflicts());
        $chosenLanguage = $request->getChosenLanguage();
        $existingTranslations = $this->translationService->getAllTranslationsForGivenLang($chosenLanguage);

        if (! $this->translationService->validImportFile($resolvedConflicts, $chosenLanguage)) {
            return response()->json(___('custom-app', 'Wrong syntax in your import'), 409);
        }

        return $this->translationService->checkAndUpdateTranslations($chosenLanguage, $existingTranslations, $resolvedConflicts);
    }
}
