<?php

namespace CustomPackages\CustomApp\Commands\Trait;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait BuildCrudGeneratorTrait
{
    /**
     * Get columns option and return columns array
     */
    protected function getOptionArray(string $optionName): array
    {
        return array_map('trim', explode(",", $this->option($optionName)));
    }

    /**
     * Map form columns into request rules array
     */
    protected function buildRulesList(): Collection
    {
        return $this->formColumns->mapWithKeys(function ($column) {
            return [$column['name'] => $this->buildRule(
                columnType: $column['type'],
                required: $column['required']
            )];
        });
    }

    protected function setFillableColumns(string $column): void
    {
        array_push($this->fillable, "'$column'");
    }

    protected function setTranslatableColumns(string $column): void
    {
        array_push($this->translatable, "'$column'");
    }

    /**
     * Check if selected columns are available in database
     */
    protected function selectedColumnsAreAvailable(array $columns, array $availableColumns = null): bool
    {
        $columnsNotFound = collect([]);

        collect($columns)->map(function ($column) use (&$columnsNotFound, $availableColumns) {
            if (! $availableColumns) {
                $columnFoundInDb = $this->tableColumns->where("name", $column)->count();
            } else {
                $columnFoundInDb = collect($availableColumns)->where("name", $column)->count();
            }

            if (! $columnFoundInDb) {
                $columnsNotFound->push($column);
            }
        });

        if (! $columnsNotFound->isEmpty()) {
            $this->components->error("Following columns were not found in available selection: " . implode(", ", $columnsNotFound->values()->all()));
        }

        return $columnsNotFound->isEmpty();
    }

    /**
     * Map all column with data from databse
     */
    protected function mapColumnsData(array $columns): Collection
    {
        return collect($columns)->map(function ($column) {
            $columnData = $this->tableColumns->where("name", $column)->first();

            return [
                'name' => $column,
                'type' => $columnData['type'],
                'required' => $columnData['required'],
            ];
        });
    }

    /**
     * Base build replacements in views.
     */
    protected function baseBuildReplacements(): array
    {
        return [
            'modelName' => $this->className,
            'modelNamespace' => $this->modelNamespace,
            'controllerNamespace' => $this->controllerNamespace,
            'controllerName' => $this->controllerName,
            'modelNamePluralLowerCase' => Str::camel(Str::plural($this->className)),
            'modelNamePluralUpperCase' => ucfirst(Str::plural($this->className)),
            'modelNameLowerCase' => Str::camel($this->className),
            'requestNamespace' => $this->requestNamespace,
            'modelRoute' => "custom-app." . Str::kebab(Str::plural($this->className)),
            'indexRequestNamespace' => $this->indexRequestNamespace,
            'createRequestNamespace' => $this->createRequestNamespace,
            'storeRequestNamespace' => $this->storeRequestNamespace,
            'editRequestNamespace' => $this->editRequestNamespace,
            'updateRequestNamespace' => $this->updateRequestNamespace,
            'destroyRequestNamespace' => $this->destroyRequestNamespace,
            'bulkDestroyRequestNamespace' => $this->bulkDestroyRequestNamespace,
            'indexRequest' => $this->indexRequest,
            'createRequest' => $this->createRequest,
            'storeRequest' => $this->storeRequest,
            'editRequest' => $this->editRequest,
            'updateRequest' => $this->updateRequest,
            'destroyRequest' => $this->destroyRequest,
            'bulkDestroyRequest' => $this->bulkDestroyRequest,
            'showIndexColumns' => $this->indexColumns->map(function ($column) {
                return "'{$column['name']}'";
            })->implode(","),
            'hasMediaCollections' => ! empty($this->mediaCollections),
            'relations' => $this->relations ?? collect([]),
            'modelRelations' => $this->buildModelRelations(),
            'modelRelationsGetQueries' => $this->buildModelRelationsGetQueries(),
            'modelRelationsSyncQueries' => $this->buildModelRelationsSyncQueries(),
            'modelRelationsDetachQueries' => $this->modelRelationsDetachQueries(),
            'modelRelationsLoad' => $this->buildModelRelationsLoad(),
            'export' => $this->withExport,
            'exportName' => Str::plural($this->className) . "Export",
            'exportNamespace' => $this->exportNamespace,
            'exportFileName' => Str::plural($this->className),
        ];
    }

    /**
     * Build replacements for model view.
     */
    protected function modelBuildReplacements(): array
    {
        return array_merge($this->baseBuildReplacements(), [
            'fillable' => implode(', ', $this->fillable),
            'softDeletesNamespace' => $this->softDeleteNamespace,
            'softDeletes' => $this->softDelete,
            'isSoftDelete' => $this->isSoftDelete,
            'tableName' => $this->tableName,
            'mediaCollections' => $this->mediaCollections,
            'hasImageCollections' => ! empty($this->imageCollections),
            'relations' => $this->relations,
            'relations' => $this->relations ?? collect([]),
            'translatableColumns' => $this->tableColumns->filter(fn ($column) => $column['translatable'])->values()->all(),
            'translatable' => implode(', ', $this->translatable),
        ]);
    }

    /**
     * Build replacements fro requests view.
     */
    protected function requestBuildReplacements(string $requestClassName, string $request, string $permissionName): array
    {
        $rules = $this->ruleList->all();

        if ($request === "create" || $request === "edit" || $request === "destroy") {
            $rules = [];
        }

        if ($request === "index") {
            $rules = $this->indexRuleList();
        }

        if ($request === "update") {
            // Replacing 'required' rules by 'sometimes'. To make 'partial update' work
            $rules = collect($rules)->map(function ($rule) {
                return array_replace(
                    $rule,
                    array_fill_keys(
                        array_keys($rule, 'required'),
                        'sometimes'
                    )
                );
            })->toArray();
        }

        if ($request === "bulkDestroy") {
            $permissionName = "destroy";
            $rules = $this->bulkDestoryRuleList();
        }

        return array_merge($this->baseBuildReplacements(), [
            'requestClassName' => $requestClassName,
            'requestNamespace' => $this->requestNamespace,
            'rules' => $rules,
            'permissionName' => "custom-app." . Str::kebab($this->className) . ".$permissionName",
        ]);
    }

    /**
     * Build replacements for index request.
     */
    protected function indexRuleList(): array
    {
        return [
            'search' => ['sometimes', 'string'],
            'per_page' => ['sometimes', 'integer'],
            'bulk_select_all' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Build replacements for bulk destroy request.
     */
    protected function bulkDestoryRuleList(): array
    {
        return [
            'ids' => ['required', 'array'],
        ];
    }

    /**
     * Build replacements for route view.
     */
    protected function routesBuildReplacements(): array
    {
        return array_merge($this->baseBuildReplacements(), [
            'fullNameControllerClass' => "$this->controllerNamespace\\{$this->controllerName}::class",
            'routeVariable' => "{" . Str::camel($this->className) . "}",
            'baseUrl' => 'admin', //Todo: this add config ?
            'routeName' => Str::kebab(Str::plural($this->className)),
        ]);
    }

    /**
     * Build replacements for vue views.
     */
    protected function viewsBuildReplacements(): array
    {
        $baseRouteName = "custom-app." . Str::kebab(Str::plural($this->className));

        return [
            '[[modelIndexRoute]]' => "{$baseRouteName}.index",
            '[[modelCreateRoute]]' => "{$baseRouteName}.create",
            '[[modelEditRoute]]' => "{$baseRouteName}.edit",
            '[[modelStoreRoute]]' => "{$baseRouteName}.store",
            '[[modelUpdateRoute]]' => "{$baseRouteName}.update",
            '[[modelBulkDestroyRoute]]' => "{$baseRouteName}.bulk-destroy",
            '[[modelDestroyRoute]]' => "{$baseRouteName}.destroy",
            '[[modelName]]' => Str::headline($this->className),
            '[[modelNamePlural]]' => Str::headline(Str::plural($this->className)),
            '[[modelNameLowerCase]]' => Str::camel($this->className),
            '[[modelNamePluralLowerCase]]' => Str::camel(Str::plural($this->className)),
            '[[listingHeaderCell]]' => $this->buildVueIndexHeader(),
            '[[listingDataCell]]' => $this->buildVueIndexColumns(),
            '[[modelIndexName]]' => ucfirst($this->className),
            '[[indexColumnsTypeScript]]' => $this->getTypeScriptColumns($this->tableColumns),
            '[[formColumnsTypeScript]]' => $this->getTypeScriptColumns($this->formColumns),
            '[[typescriptImports]]' => $this->getTypeScriptImports(),
            '[[modelPermissionName]]' => Str::kebab($this->className),
            '[[createFormColumns]]' => $this->buildFormDefaultColumns(),
            '[[editFormColumns]]' => $this->buildFormDefaultColumns(edit: true),
            '[[modelFullForm]]' => $this->buildFullForm(),
            '[[indexTypescriptExport]]' => ! empty($this->mediaCollections) ? "media?: UploadedFile[];" : "",
            '[[exportButton]]' => $this->buildExportButton(),
            '[[exportFunctionality]]' => $this->buildExportFunctionality(),
            '[[editVueImports]]' => $this->getEditVueImports(),
            '[[relationsProps]]' => $this->relations?->map(function ($relation) {
                return "{$relation['optionsName']}: Array<{value: string|number, label: string}>";
            })->implode(";\n") ?? '',
            '[[relationsFormProps]]' => $this->relations?->map(function ($relation) {
                return ":{$relation['optionsName']}=\"{$relation['optionsName']}\"";
            })->implode(" ") ?? '',
            '[[exportButton]]' => $this->buildExportButton(),
            '[[exportFunctionality]]' => $this->buildExportFunctionality(),
            '[[translatableFunctionality]]' => $this->buildTranslatableFunctionality(),
            '[[translatableLocaleSwitcher]]' => $this->buildTranslatableLocaleSwitcher(),
        ];
    }

    /**
     * Build replacement for permission migration.
     */
    protected function buildPermissionMigrationReplace(): array
    {
        return [
            'permissionsList' =>
            collect(['index', 'create', 'edit', 'destroy'])->map(function ($permission) {
                return "custom-app." . Str::kebab($this->className) . ".$permission";
            }),
            'permissionGuardName' => 'custom-app',
        ];
    }

    protected function buildExportReplace(): array
    {
        return array_merge($this->baseBuildReplacements(), [
            'exportColumns' => $this->buildExportColumns(),
        ]);
    }

    /**
     * Build header for index vue view.
     */
    protected function buildVueIndexHeader(): mixed
    {
        return $this->indexColumns->map(function ($column) {
            $sortable = in_array($column['name'], $this->sortableColumns) ? " sortBy=\"{$column["name"]}\"" : "";
            $relation = $this->relations?->firstWhere('foreignKey', $column['name']);

            $columnName = $relation ? Str::headline($relation["model"]) : Str::headline($column["name"]);

            return '
        <ListingHeaderCell' . $sortable . '>
            {{ $t("custom-app", "' . $columnName . '") }}
        </ListingHeaderCell>';
        })->implode(" ");
    }

    /**
     * Build content for index vue view.
     */
    protected function buildVueIndexColumns(): mixed
    {
        return $this->indexColumns->map(function ($column) {
            $showDateFormat = $this->getDateFormatWithColumnType($column['type']);
            $dataCellContent = ' {{ item.' . $column["name"] . ' }}';
            $updateRoute = Str::kebab(Str::plural($this->className)) . '.update';

            if ($column['translatable']) {
                $dataCellContent = ' {{ item.' . $column["name"] . '?.[currentLocale] }}';
            }

            if ($showDateFormat) {
                $dataCellContent = " {{ dayjs(item.{$column["name"]}).format('$showDateFormat') }}";
            }

            if ($relation = $this->relations?->firstWhere(fn ($relation) => $relation['foreignKey'] === $column['name'] && $relation['type'] === 'belongsTo')) {
                $dataCellContent = ' {{ item.' . Str::snake($relation['model']) . '.' .  $relation['label'] . ' }}';
            }

            if ($column['publishable']) {
                $datePickerMode = $column["type"] === 'date' ? 'date' : 'dateTime';
                $dataCellContent =
                    '<Publish :publishedAt="item.' . $column["name"] . '" :updateUrl="route(\'custom-app.' . $updateRoute . '\', item.id)" columnName="' . $column["name"] . '" mode="' . $datePickerMode . '"/>';
            }

            if ($column['type'] === 'boolean') {
                $dataCellContent =
                    '<ListingToggle name="'.$column['name'].'" v-model="item.'.$column['name'].'" :updateUrl="route(\'custom-app.' . $updateRoute . '\', item.id)" />';
            }

            return '
        <ListingDataCell>
            ' .  $dataCellContent . '
        </ListingDataCell>';
        })->implode(" ");
    }

    protected function getEditVueImports(): string
    {
        $return = "";
        if (! empty($this->mediaCollections)) {
            $return .= 'import {getMediaCollection} from "custom-app/helpers";';
        }

        return $return;
    }

    /**
     * Return rules for requests.
     */
    protected function buildRule(string $columnType, bool $required): array
    {
        $rules = collect([]);
        $rules->push($required ? 'required' : 'nullable');

        switch ($columnType) {
            case "array":
                $rules->push('array');

                break;
            case "string":
                $rules->push('string');

                break;
            case "boolean":
                $rules->push("boolean");

                break;
        }

        return $rules->all();
    }

    protected function buildExportColumns(): array
    {
        return $this->tableColumns->where('hidden', false)->map(function ($column) {
            return Str::headline($column['name']);
        })->all();
    }

    protected function buildExportButton(): string
    {
        if ($this->withExport) {
            return '<Button
      :leftIcon="ArrowDownTrayIcon"
      as="a"
      class="ml-2"
      @click="downloadFile"
    >
      {{ $t("custom-app", "Export") }}
    </Button>';
        }

        return "";
    }

    protected function buildExportFunctionality(): string
    {
        if ($this->withExport) {
            return 'const downloadFile = () => {
    const url = window.location.href.split("?");
    if(url.length > 1) {
      window.location = route(\'custom-app.' . Str::kebab(Str::plural($this->className)) . '.export\', url.pop()).slice(0, -1);
    } else {
      window.location = route(\'custom-app.' . Str::kebab(Str::plural($this->className)) . '.export\');
    }
}';
        }

        return "";
    }

    protected function buildTranslatableFunctionality(): string
    {
        if (count($this->translatable) > 0) {
            return "
import { useFormLocale } from \"custom-app/hooks/useFormLocale\"; \n\n
const { availableLocales, currentLocale, translatableDefaultValue, getLabelWithLocale } = useFormLocale();
            ";
        }

        return "";
    }

    protected function buildTranslatableLocaleSwitcher(): string
    {
        if (count($this->translatable) > 0) {
            return '<CardLocaleSwitcher v-model="currentLocale" class="mb-6" />';
        }

        return "";
    }

    /**
     * Get columns for typescript types.d.ts file.
     */
    protected function getTypeScriptColumns(Collection $columns): mixed
    {
        return $columns->map(function ($column) {
            if ($column['name'] === 'id') {
                return "{$column['name']}: string | number";
            }

            switch ($column['type']) {
                case 'json':
                case 'jsonb':
                    $columnType = 'Record<string, string>';

                    break;
                case 'text':
                case 'datetime':
                case 'bigint':
                    $columnType = 'string';

                    break;
                case 'media':
                    $columnType = 'Array<UploadedFile>';

                    break;
                default:
                    $columnType = $column['type'];

                    break;
            }

            return "{$column['name']}: {$columnType}";
        })->filter()->implode("; \n");
    }

    protected function getTypeScriptImports(): string
    {
        $return = "";
        if (! empty($this->mediaCollections)) {
            $return .= 'import type { UploadedFile } from "custom-app/types";';
        }

        return $return;
    }

    /**
     * Map all column with data from databse
     */
    protected function mapRelationsData(array $columns): Collection
    {
        return collect($columns)->map(function ($column) {
            $parts = explode('(', $column);
            $type = lcfirst($parts[0]);
            $parts = explode(',', trim($parts[1], ')'));

            [$relatedModel, $modelAccesor] = explode('->', $parts[0]);

            // check related model exist namespaced model or not and check class exist
            if ((strpos($relatedModel, "\\") === false && ! class_exists("App\\Models\\$relatedModel")) || (strpos($relatedModel, "\\") !== false && ! class_exists($relatedModel))) {
                $this->components->error("Selected related model $relatedModel does not exist!");
                exit(1);
            }


            if ($type === 'belongsToMany') {
                $name = Str::camel(Str::plural($relatedModel));
                $foreignKey = isset($parts[2]) ? $parts[2] : Str::snake("{$this->className}_id");
                $ownerKey = isset($parts[3]) ? $parts[3] : Str::snake("{$relatedModel}_id");
                $tableName = isset($parts[1]) ? $parts[1] : Str::lower(Arr::join(Arr::sort([Str::snake($this->className), Str::snake($relatedModel)]), '_'));
            } else {
                $name = Str::camel($relatedModel);
                $foreignKey = isset($parts[1]) ? $parts[1] : Str::snake("{$name}_id");
                $ownerKey = isset($parts[2]) ? $parts[2] : "id";
                $tableName = Str::snake(Str::plural($relatedModel));
            }

            return [
                'name' => $name,
                'type' => $type,
                'model' => $relatedModel,
                'tableName' => $tableName,
                'foreignKey' => $foreignKey,
                'ownerKey' => $ownerKey,
                'label' => $modelAccesor,
                'optionsName' => "{$name}Options",
                'namespace' => strpos('\\', $relatedModel) === false ? "\\App\\Models\\$relatedModel" : $relatedModel,
            ];
        });
    }

    /**
     * Build model relations.
     */
    protected function buildModelRelations(): string
    {
        $this->ruleList = $this->ruleList->merge($this->relations?->where('type', 'belongsToMany')->mapWithKeys(
            fn ($relation) => ["{$relation['name']}_ids" => $this->buildRule(
                columnType: 'array',
                required: false,
            )]
        ));

        // TODO: refactor
        $this->relations?->where('type', 'belongsTo')->each(function ($relation) {
            if (! in_array("exists:{$relation['tableName']},{$relation['ownerKey']}", $this->ruleList[$relation['foreignKey']])) {
                $this->ruleList[$relation['foreignKey']] = [
                    ...$this->ruleList[$relation['foreignKey']],
                    "exists:{$relation['tableName']},{$relation['ownerKey']}",
                ];
            }
        });

        return $this->relations?->map(function ($relation) {
            $foreignKey = $relation['foreignKey'] ? ", '{$relation['foreignKey']}'" : null;
            $ownerKey = $relation['ownerKey'] ? ", '{$relation['ownerKey']}'" : null;
            $tableName = $relation['tableName'] ? ", '{$relation['tableName']}'" : null;

            if ($relation['type'] === 'belongsToMany') {
                $relationDefinition = "{$relation['namespace']}::class{$tableName}{$foreignKey}{$ownerKey}";
            } else {
                $relationDefinition = "{$relation['namespace']}::class{$foreignKey}{$ownerKey}";
            }

            return "
    public function {$relation['name']}(): " . ucfirst($relation['type']) . "
    {
        return \$this->" . lcfirst($relation['type']) . "({$relationDefinition});
    }";
        })->implode("\n\n") ?? "";
    }

    protected function buildModelRelationsGetQueries(): string
    {
        return $this->relations?->map(function ($relation) {
            return "'{$relation['optionsName']}' => {$relation['model']}::all()->map(fn (\$model) => ['value' => \$model->id, 'label' => \$model->{$relation['label']}]),";
        })->implode("\n") ?? "";
    }

    protected function buildModelRelationsSyncQueries(): string
    {
        $modelName = Str::camel($this->className);

        return $this->relations?->where('type', 'belongsToMany')->map(function ($relation) use ($modelName) {
            return "
        if (\$request->input('{$relation['name']}_ids')) {
            \${$modelName}->{$relation['name']}()->sync(\$request->input('{$relation['name']}_ids'));
        }";
        })->implode("\n") ?? "";
    }

    protected function modelRelationsDetachQueries(): string
    {
        $modelName = Str::camel($this->className);

        return $this->relations?->where('type', 'belongsToMany')->map(function ($relation) use ($modelName) {
            return "
        \${$modelName}->{$relation['name']}()->detach();
        ";
        })->implode("\n") ?? "";
    }

    protected function buildModelRelationsLoad(): string
    {
        $modelName = Str::camel($this->className);

        if (! $this->relations?->where('type', 'belongsToMany')->count()) {
            return "";
        }

        $relationsNamesToLoad = $this->relations
            ->where('type', 'belongsToMany')
            ->map(fn ($relation) => "'{$relation['name']}'")
            ->implode(", ");

        return "\${$modelName}->load({$relationsNamesToLoad});";
    }
}
