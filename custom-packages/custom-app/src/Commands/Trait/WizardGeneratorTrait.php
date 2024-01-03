<?php

namespace CustomPackages\CustomApp\Commands\Trait;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait WizardGeneratorTrait
{
    protected function getAndSetListingColumns()
    {
        $choices = $this->indexColumns->map->name->toArray();
        if ($this->shouldAskForOptionValue('listing-columns')) {
            $selectedColumns = $this->components->choice(
                question: "Which columns should be visible on the listing page?",
                choices: $choices,
                default: implode(",", $choices),
                multiple: true,
            );
        } else {
            $selectedColumns = $this->option('listing-columns') ? $this->getOptionArray('listing-columns') : $this->onlyTableColumns;

            if ($missingColumn = collect($selectedColumns)->first(function ($col) use ($choices) {
                return ! in_array($col, $choices);
            })) {
                $this->components->error("Listing column $missingColumn does not exist, please try again");
                exit(1);
            }
        }

        if (is_bool(array_search("id", $selectedColumns))) {
            array_unshift($selectedColumns, 'id');
        }

        if (array_search("password", $selectedColumns) !== false) {
            $selectedColumns = array_merge(array_diff($selectedColumns, ["password"]));
        }

        if (! $this->selectedColumnsAreAvailable($selectedColumns)) {
            $this->getAndSetListingColumns();
        } else {
            $this->indexColumns = $this->mapColumnsData($selectedColumns);
        }

        return $this;
    }

    protected function getAndSetSortableColumns()
    {
        if ($this->shouldAskForOptionValue('sortable-columns')) {
            $this->sortableColumns = $this->components->choice(
                question: "Which columns should be sortable in the listing page?",
                choices: $this->indexColumns->map(fn ($column) => $column['name'])->values()->all(),
                default: implode(",", $this->indexColumns->map(fn ($column) => $column['name'])->values()->all()),
                multiple: true,
            );
        } else {
            $sortableColumns = $this->option('sortable-columns') ? $this->getOptionArray('sortable-columns') : $this->indexColumns->map(fn ($column) => $column['name'])->values();

            if ($missingColumn = collect($sortableColumns)->first(function ($col) {
                return ! in_array($col, $this->indexColumns->map(fn ($column) => $column['name'])->all());
            })) {
                $this->components->error("Column $missingColumn does not exist or it's not within index columns, please try again");
                exit(1);
            }
        }

        return $this;
    }

    protected function getAndSetFormColumns()
    {
        $availableColumns = collect($this->onlyTableColumns)->where(fn ($column) => $column !== 'id')->values()->all();

        if ($this->shouldAskForOptionValue('form-columns')) {
            $selectedColumns = $this->components->choice(
                question: "Which columns should be visible on the form page?",
                choices: $availableColumns,
                default: implode(",", $availableColumns),
                multiple: true,
            );
        } else {
            $selectedColumns = $this->option('form-columns') ? $this->getOptionArray('form-columns') : $availableColumns;

            if ($missingColumn = collect($selectedColumns)->first(function ($col) {
                return ! in_array($col, $this->onlyTableColumns);
            })) {
                $this->components->error("Form column $missingColumn does not exist, please, try again");
                exit(1);
            }
        }

        if (! $this->selectedColumnsAreAvailable($selectedColumns)) {
            $this->getAndSetFormColumns();
        } else {
            $this->formColumns = $this->mapColumnsData($selectedColumns);
            $this->ruleList = $this->buildRulesList();
        }

        return $this;
    }

    protected function getAndSetTranslatableColumns()
    {
        // Only JSON columns can be translatable
        $jsonColumnsNames = $this->tableColumns
            ->whereIn('type', ['json', 'jsonb'])
            ->map(function ($column, $key) {
                return $column['name'];
            })
            ->values()
            ->all();

        if (! empty($jsonColumnsNames)) {
            if ($this->shouldAskForOptionValue('translatable-columns')) {
                $selectedColumns = $this->components->choice(
                    question: "Which columns should be translatable (select only columns of type json or jsonb)?",
                    choices: ['none', ...$jsonColumnsNames],
                    default: implode(",", $jsonColumnsNames),
                    multiple: true,
                );
            } else {
                $selectedColumns = $this->option('translatable-columns') ? $this->getOptionArray('translatable-columns') : $jsonColumnsNames;

                if (collect($selectedColumns)->diff($jsonColumnsNames)->isNotEmpty()) {
                    $this->components->error("Not all selected columns are of type json or jsonb, please, try again.");
                    exit(1);
                }
            }
        } else {
            $selectedColumns = [];
        }

        // TODO: Refactor
        $this->tableColumns = $this->tableColumns->map(function ($column) use ($selectedColumns) {
            if (in_array($column['name'], $selectedColumns)) {
                $column['translatable'] = true;
                $this->setTranslatableColumns($column['name']);
            }

            return $column;
        });

        $this->indexColumns = $this->indexColumns->map(function ($column) use ($selectedColumns) {
            if (in_array($column['name'], $selectedColumns)) {
                $column['translatable'] = true;
            } else {
                $column['translatable'] = false;
            }

            return $column;
        });

        $this->formColumns = $this->formColumns->map(function ($column) use ($selectedColumns) {
            if (in_array($column['name'], $selectedColumns)) {
                $column['translatable'] = true;
            } else {
                $column['translatable'] = false;
            }

            return $column;
        });

        return $this;
    }

    protected function getAndSetPublishableColumn()
    {
        // Only date and dateTime columns can be publishable
        $dateTimeColumnsNames = $this->tableColumns
            ->where('hidden', false)
            ->whereIn('type', ['datetime', 'date'])
            ->map(function ($column, $key) {
                return $column['name'];
            })
            ->values()
            ->all();

        if (! empty($dateTimeColumnsNames)) {
            if ($this->shouldAskForOptionValue('publishable-column')) {
                $selectedColumn = $this->components->choice(
                    question: "Which column should be used for publishable feature (only date or dateTime columns)?",
                    choices: ['none', ...$dateTimeColumnsNames],
                    default: collect($dateTimeColumnsNames)->first(fn ($column) => $column === 'published_at') ?? 'none',
                );
            } else {
                $selectedColumn = $this->option('publishable-column') ?
                    $this->option('publishable-column') : (in_array("published_at", $dateTimeColumnsNames) ? "published_at" : "none");

                if ($selectedColumn !== "none" && ! in_array($selectedColumn, $dateTimeColumnsNames)) {
                    $this->components->error("Selected publishable column {$selectedColumn} is not type dateTime");
                    exit(1);
                }
            }
        } else {
            $selectedColumn = 'none';
        }

        $selectedColumn = $selectedColumn === 'none' ? null : $selectedColumn;


        // TODO: Refactor
        $this->tableColumns = $this->tableColumns->map(function ($column) use ($selectedColumn) {
            if ($column['name'] === $selectedColumn) {
                $column['publishable'] = true;
            }

            return $column;
        });

        $this->indexColumns = $this->indexColumns->map(function ($column) use ($selectedColumn) {
            if ($column['name'] === $selectedColumn) {
                $column['publishable'] = true;
            } else {
                $column['publishable'] = false;
            }

            return $column;
        });

        $this->formColumns = $this->formColumns->map(function ($column) use ($selectedColumn) {
            if ($column['name'] === $selectedColumn) {
                $column['publishable'] = true;
            } else {
                $column['publishable'] = false;
            }

            return $column;
        });

        return $this;
    }

    protected function getAndSetTaggable()
    {
        if ($this->shouldAskForOptionValue('taggable')) {
            $taggable = $this->components->choice(
                question: "Do you want to use taggable feature?",
                choices: ['yes', 'no'],
                default: 'no',
            );
        } else {
            $taggable = $this->option('taggable') ? 'yes' : 'no';
        }

        return $this;
    }

    protected function getAndSetMediaCollections()
    {
        if ($this->shouldAskForOptionValue('media-collections')) {
            $mediaCollections = $this->components->ask(
                "Write name of media collections you want to register. Separate them by comma or leave empty if you don't want any media collections."
            );
        } else {
            $mediaCollections = $this->option('media-collections');
        }

        $this->mediaCollections = $mediaCollections ? array_map('trim', explode(',', $mediaCollections)) : [];

        $mediaColumns = collect($this->mediaCollections)->map(function ($media) {
            return [
                'name' => $media,
                'type' => 'media',
                'required' => false,
                'translatable' => false,
            ];
        });

        $this->formColumns = $this->formColumns->merge($mediaColumns);

        return $this;
    }

    protected function getAndSetImageCollections()
    {
        if ($this->shouldAskForOptionValue('image-collections') && ! empty($this->mediaCollections)) {
            $imageCollections = $this->components->choice(
                question: "Select which of those media collections should be of type image?",
                choices: [...$this->mediaCollections, 'none'],
                multiple: true,
            );
        } else {
            $imageCollections = $this->option('image-collections') ? $this->getOptionArray('image-collections') : 'none';
        }

        $this->imageCollections = $imageCollections !== 'none' ? $imageCollections : [];

        return $this;
    }

    protected function getAndSetRelations()
    {
        $modelNamespace = "$this->modelNamespace\\";
        if ($this->shouldAskForOptionValue('add-relation')) {
            $question = empty($this->relations) ? 'Do you want to add a relation?' : 'Do you want to add another relation?';

            if ($this->components->choice(
                question: $question,
                choices: ['yes', 'no'],
                default: 'no'
            ) === 'yes') {
                $relation = [];
                $models = collect([]);
                $customRelatedModel = false;
                $intColumns = $this->tableColumns->whereIn('type', ['int', 'bigint'])->map(fn ($column) => $column['name']);

                collect(File::allFiles(app_path("Models")))->each(function ($file) use (&$models, $modelNamespace) {
                    $rel = $file->getRelativePathName();

                    $class = $modelNamespace.implode('\\', explode('/', substr($rel, 0, strrpos($rel, '.'))));

                    if (class_exists($class)) {
                        $models->put(
                            $class,
                            substr($class, strlen($modelNamespace))
                        );
                    }
                });

                collect([
                    "$modelNamespace$this->className" => $this->className,
                    "CustomPackages\CustomApp\Models\CraftableProUser" => "CraftableProUser",
                    "\Custom\Namespace" => "custom",
                ])->map(fn ($name, $namespace) => $models->put($namespace, $name))->sort();

                $relation['type'] = lcfirst($this->components->choice(
                    question: "What is the type of the relation?",
                    choices: ['belongsTo', 'belongsToMany'],
                    default: 'belongsTo'
                ));


                $relation['namespace'] = $this->components->choice(
                    question: "What is the name of the related model?",
                    choices: $models->all(),
                );


                $relation['model'] = $models->get($relation['namespace']);

                if ($relation['model'] === 'custom') {
                    $customRelatedModel = true;
                    $custom = $this->askCustomRelation();

                    $relation['model'] = class_basename(app($custom));
                    $relation['namespace'] = $custom;
                }

                $relation['name'] = $relation['type'] === 'belongsTo' ? Str::camel($relation['model']) : Str::camel(Str::plural($relation['model']));
                $relation['optionsName'] = "{$relation['name']}Options";

                $primary_key = class_exists($relation['namespace']) ? app($relation['namespace'])->getKeyName() : "id";

                if ($relation['type'] === 'belongsToMany') {
                    $relation['tableName'] = $this->components->ask(
                        question: "What is the name of the pivot table?",
                        default: Str::lower(Arr::join(Arr::sort([Str::snake($this->className), Str::snake($relation['model'])]), '_'))
                    );

                    $relation['foreignKey'] = $this->components->ask(
                        question: "What is the foreign pivot key in '{$relation['tableName']}' table?",
                        default: Str::lower(Str::snake($this->className)) . "_$primary_key"
                    );

                    $relation['ownerKey'] = $this->components->ask(
                        question: "What is the related pivot key in '{$relation['tableName']}' table?",
                        default: Str::lower(Str::snake($relation['model'])) . "_$primary_key"
                    );
                } else {
                    $relation['tableName'] = class_exists($relation['namespace']) ? app($relation['namespace'])->getTable() : Str::snake(Str::plural($relation['model']));

                    $relation['foreignKey'] = $this->components->choice(
                        question: "What is the foreign key column in {$this->className} model?",
                        choices: $intColumns->values()->all(),
                        default: $intColumns->first(fn ($column) => $column === Str::lower("{$relation['model']}_$primary_key")) ?? $intColumns->first(),
                    );
                }

                if ($customRelatedModel) {
                    $relation['ownerKey'] = $relation['ownerKey'] ?? $this->components->ask(
                        question: "What is the referenced primary key column in {$relation['model']} model?",
                        default: 'id'
                    );

                    $relation['label'] = $this->components->ask(
                        question: "Which attribute should represent the {$relation['model']} model in {$this->className} module (typically 'name' or 'title')?",
                    );
                } else {
                    $relationTableColumns = $this->getTableColumns($relation['type'] === 'belongsToMany' ? Str::snake(Str::plural($relation['model'])) : $relation['tableName'])->where('hidden', false);
                    $relationIntColumns = $relationTableColumns->whereIn('type', ['int', 'bigint'])->map(fn ($column) => $column['name'])->values()->all();
                    $relationStringColumns = $relationTableColumns->whereIn('type', ['string'])->map(fn ($column) => $column['name'])->values()->all();

                    $relation['ownerKey'] = $relation['ownerKey'] ?? $this->components->choice(
                        question: $relation['type'] === 'belongsTo' ? "What is the referenced primary key column in {$relation['model']} model?" : "What is the related pivot key in '{$relation['tableName']}' table?",
                        choices: $relationIntColumns,
                        default: $relationIntColumns[0],
                    );

                    $relation['label'] = $this->components->choice(
                        question: "Which attribute should represent the {$relation['model']} model in {$this->className} module (typically 'name' or 'title')?",
                        choices: $relationStringColumns,
                        default: $relationStringColumns ? $relationStringColumns[0] : null,
                    );
                }

                if (! $this->relations) {
                    $this->relations = collect([]);
                }

                $this->relations->push($relation);

                $this->getAndSetRelations();
            }
        } else {
            $this->relations = $this->mapRelationsData($this->option('add-relation'));
        }

        return $this;
    }

    protected function getAndSetWithExport()
    {
        if ($this->shouldAskForOptionValue('with-export')) {
            $this->withExport = $this->components->choice(
                question: "Do you want to use export feature?",
                choices: ['yes', 'no'],
                default: 'no',
            ) === 'yes';
        } else {
            $this->withExport = $this->option('with-export');
        }

        return $this;
    }

    protected function askCustomRelation(): string
    {
        $custom = $this->components->ask(
            question: "What is the name of the custom related model? (you can use \ as a namespace delimiter; namespaces can be relative to $this->modelNamespace or absolute)",
        );

        if (! class_exists($custom)) {
            $this->components->error("Custom related model $custom doest not exist");
            $this->askCustomRelation();
        }

        return $custom;
    }
}
