<?php

namespace CustomPackages\CustomApp\Commands\Trait;

use Illuminate\Support\Str;

trait FormCrudGenratorHelperTrait
{
    /**
     * Build typescript form columns. Usage for create/edit vue files.
     *
     * @param bool $edit
     * @return mixed
     */
    private function buildFormDefaultColumns(bool $edit = false): mixed
    {
        $model = Str::camel($this->className);

        return $this->formColumns->map(function ($column) use ($edit, $model) {
            $defaultValue = $column['translatable'] ? '{ ...translatableDefaultValue }' : '""';

            switch ($column['type']) {
                case 'json':
                    $columnDefault = $edit ? 'props.' . $model . '?.' . $column["name"] . ' ?? ' . $defaultValue : $defaultValue;

                    break;
                case 'text':
                    $columnDefault = $edit ? 'props.' . $model . '?.' . $column["name"] . ' ?? ' . $defaultValue : $defaultValue;

                    break;
                case 'datetime':
                    $columnDefault = $edit ? 'props.' . $model . '?.' . $column["name"] . ' ?? ' . $defaultValue : $defaultValue;

                    break;
                case 'bigint':
                    $columnDefault = $edit ? 'props.' . $model . '?.' . $column["name"] . ' ?? ' . $defaultValue : $defaultValue;

                    break;
                case "boolean":
                    $columnDefault = $edit ? 'props.' . $model . '?.' . $column["name"] . ' ?? false' : "false";

                    break;
                case "media":
                    $columnDefault = $edit ? "getMediaCollection(props.$model?.media, '{$column['name']}')" : "[]";

                    break;
                default:
                    $columnDefault = $edit ? 'props.' . $model . '?.' . $column["name"] . ' ?? ' . $defaultValue : $defaultValue;

                    break;
            }

            return "{$column['name']}: {$columnDefault}";
        })->filter()->merge(
            $this->relations?->where('type', 'belongsToMany')->map(function ($relation) use ($edit, $model) {
                $columnDefault = $edit ? 'props.' . $model . '?.' . $relation['name'] . '.map(item => item.id) ?? []' : '[]';

                return "{$relation['name']}_ids: {$columnDefault}";
            })->filter()
        )->implode(", \n");
    }

    /**
     * Build form for Form vue. Uses FormCrudGeneratorHelper.
     *
     * @return mixed
     */
    private function buildFullForm(): mixed
    {
        return $this->formColumns->map(function ($column) {
            switch ($column['type']) {
                case 'json':
                    if (in_array($column['name'], ['content', 'body', 'text'])) {
                        $input = $this->wysiwigInput($column['name'], $column['translatable']);
                    } else {
                        $input = $this->textInput($column['name'], $column['translatable']);
                    }

                    break;
                case 'text':
                    if (in_array($column['name'], ['content', 'body', 'text'])) {
                        $input = $this->wysiwigInput($column['name'], $column['translatable']);
                    } else {
                        $input = $this->textAreaInput($column['name'], $column['translatable']);
                    }

                    break;
                case 'datetime':
                    $input = $this->dateTime($column['name']);

                    break;
                case 'date':
                    $input = $this->dateTime($column['name'], 'date');

                    break;
                case "boolean":
                    $input = $this->checkbox($column['name']);

                    break;
                case "media":
                    $input = $this->dropzone($column['name']);

                    break;
                default:
                    if ($relation = $this->relations?->firstWhere(fn ($relation) => $relation['foreignKey'] === $column['name'] && $relation['type'] === 'belongsTo')) {
                        $input = $this->select($relation['foreignKey'], $relation['optionsName'], $relation['model']);

                        break;
                    }
                    $input = $this->textInput($column['name'], $column['translatable']);

                    break;
            }

            return $input;
        })->filter()->merge(
            $this->relations?->where('type', 'belongsToMany')->map(function ($relation) {
                return $this->multiSelect("{$relation['name']}_ids", $relation['optionsName'], Str::plural($relation['model']));
            })
        )->implode(" \n");
    }

    /**
     * Text input.
     *
     * @param string $columnName
     * @return string
     */
    private function textInput(string $columnName, bool $isTranslatable): string
    {
        $label = $isTranslatable ?
            ":label=\"" . 'getLabelWithLocale($t(\'custom-app\', \'' . Str::headline($columnName) . '\'))' . "\"" :
            ":label=\"" . '$t(\'custom-app\', \'' . Str::headline($columnName) . '\')' . "\"";

        $vModel = $isTranslatable ? "{$columnName}[currentLocale]" : $columnName;

        $type = $columnName === 'password' ? 'type="password"' : '';

        $name = $isTranslatable ? ":name=\"`{$columnName}.\${currentLocale}`\"" : "name=\"{$columnName}\"";

        return "
            <TextInput
                v-model=\"form.$vModel\"
                $name
                $label
                $type
            />";
    }

    /**
     * @param string $columnName
     * @return string
     */
    private function wysiwigInput(string $columnName, bool $isTranslatable): string
    {
        $label = $isTranslatable ?
            ":label=\"" . 'getLabelWithLocale($t(\'custom-app\', \'' . Str::headline($columnName) . '\'))' . "\"" :
            ":label=\"" . '$t(\'custom-app\', \'' . Str::headline($columnName) . '\')' . "\"";

        $vModel = $isTranslatable ? "{$columnName}[currentLocale]" : $columnName;

        $name = $isTranslatable ? ":name=\"`{$columnName}.\${currentLocale}`\"" : "name=\"{$columnName}\"";

        return "
            <Wysiwyg
                v-model=\"form.$vModel\"
                $name
                $label
            />";
    }

    /**
     * @param string $columnName
     * @return string
     */
    private function textAreaInput(string $columnName, bool $isTranslatable): string
    {
        $label = $isTranslatable ?
            ":label=\"" . 'getLabelWithLocale($t(\'custom-app\', \'' . Str::headline($columnName) . '\'))' . "\"" :
            ":label=\"" . '$t(\'custom-app\', \'' . Str::headline($columnName) . '\')' . "\"";

        $vModel = $isTranslatable ? "{$columnName}[currentLocale]" : $columnName;

        $name = $isTranslatable ? ":name=\"`{$columnName}.\${currentLocale}`\"" : "name=\"{$columnName}\"";

        return "
            <TextArea
                v-model=\"form.$vModel\"
                $name
                $label
            />";
    }

    /**
     * @param string $columnName
     * @return string
     */
    private function checkbox(string $columnName): string
    {
        return "
            <Checkbox
                v-model=\"form.$columnName\"
                name=\"$columnName\"
                :label=\"" . '$t(\'custom-app\', \'' . Str::headline($columnName) . '\')' . "\"
            />";
    }

    private function select(string $columnName, string $optionsName, string $label = null): string
    {
        $label = $label ?? Str::headline($columnName);

        return "
            <Multiselect
                v-model=\"form.$columnName\"
                name=\"$columnName\"
                :label=\"" . '$t(\'custom-app\', \'' . $label . '\')' . "\"
                :options=\"$optionsName\"
                mode=\"single\"
            />";
    }

    private function multiSelect(string $columnName, string $optionsName, string $label = null): string
    {
        $label = $label ?? Str::headline($columnName);

        return "
            <Multiselect
                v-model=\"form.$columnName\"
                name=\"$columnName\"
                :label=\"" . '$t(\'custom-app\', \'' . $label . '\')' . "\"
                :options=\"$optionsName\"
            />";
    }

    /**
     * @param string $columnName
     * @param string $mode
     * @return string
     */
    private function dateTime(string $columnName, string $mode = 'dateTime'): string
    {
        return "
            <DatePicker
                v-model=\"form.$columnName\"
                name=\"$columnName\"
                mode=\"$mode\"
                :label=\"" . '$t(\'custom-app\', \'' . Str::headline($columnName) . '\')' . "\"
            />";
    }

    protected function dropzone(string $columnName): string
    {
        $maxFileSize = config("media-library.max_file_size", 1024 * 1024 * 10);

        return "
            <Dropzone
                v-model=\"form.$columnName\"
                name=\"$columnName\"
                :maxFileSize=\"$maxFileSize\"
                :label=\"" . '$t(\'custom-app\', \'' . Str::headline($columnName) . '\')' . "\"
            />";
    }

    protected function imageUpload(string $columnName): string
    {
        return "
            <ImageUpload
                v-model=\"form.$columnName\"
                name=\"$columnName\"
                :label=\"" . '$t(\'custom-app\', \'' . Str::headline($columnName) . '\')' . "\"
            />";
    }
}
