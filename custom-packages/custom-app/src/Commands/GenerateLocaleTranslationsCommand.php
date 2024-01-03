<?php

namespace CustomPackages\CustomApp\Commands;

use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateLocaleTranslationsCommand extends Command
{
    protected $signature = 'custom-app:generate-locale-translations';

    protected $description = 'Generate locale translations';

    public function handle()
    {
        if (! File::exists(resource_path('translations'))) {
            File::makeDirectory(resource_path('translations'));
        }

        if (! File::exists(resource_path('translations/locales'))) {
            File::makeDirectory(resource_path('translations/locales'));
        }

        $locales = collect(app(GeneralSettings::class)->available_locales)->mapWithKeys(function ($locale) {
            return [
                "$locale" => $locale,
            ];
        })->toJson();

        File::put(resource_path('translations/locales') . '/locales_translations.json', $locales);

        return Command::SUCCESS;
    }
}
