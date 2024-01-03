<?php

namespace CustomPackages\CustomApp\Translations\Scanners\External;

use CustomPackages\CustomApp\Settings\GeneralSettings;
use CustomPackages\CustomApp\Translations\Repositories\LanguageLineRepository;
use CustomPackages\CustomApp\Translations\Scanners\Contracts\ExternalScannerInterface;
use CustomPackages\CustomApp\Translations\Scanners\Contracts\ScannerInterface;
use Illuminate\Support\Facades\File;

class PhpArrayScanner implements ExternalScannerInterface
{
    private array $scannedPaths;

    private ?string $group;

    // example usage in config
    //    'translations' => [
    //        'external' => [
    //            [
    //                'group' => 'api',
    //                'scan' => [
    //                    PhpArrayScanner::class => [
    //                        'paths' => [
    //                            resource_path('lang/sk/'),
    //                        ],
    //                    ],
    //                ],
    //            ],
    //        ],
    //    ],

    public function scanAndSaveTranslations(): void
    {
        collect($this->scannedPaths)->each(function ($path) {
            if (! File::exists($path)) {
                return true;
            }

            if (File::isFile($path)) {
                $this->scanFile($path);
            } else {
                $localeDirectories = File::directories($path);

                collect($localeDirectories)->each(function ($localeDirectory) {
                    $locale = basename($localeDirectory);


                    collect(File::allFiles($localeDirectory))
                        ->filter(fn ($file) => pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'php')
                        ->each(function ($file) use ($locale) {
                            $this->scanFile($file, $locale);
                        });
                });
            }
        });
    }

    public function addScannedPaths(array $scannedPaths): ScannerInterface
    {
        $this->scannedPaths = $scannedPaths;

        return $this;
    }

    public function setGroup(string $group): ExternalScannerInterface
    {
        $this->group = $group;

        return $this;
    }

    private function scanFile(string $file, ?string $locale = null)
    {
        if (! $locale) {
            $locale = app(GeneralSettings::class)->default_locale;
        }

        $fileName = basename($file, '.php'); // filename without .php extension
        $groupName = $this->group ?? $fileName;

        // Trans function simply doesn't work here, because it return 'en' translations for every locale
        $fileContent = include $file;

        $this->saveArray($fileContent, $groupName, $locale);
    }

    private function saveArray(array $translation, string $groupName, string $locale, ?string $parentKey = null)
    {
        foreach ($translation as $subKey => $subTranslation) {
            if (is_array($subTranslation)) {
                $this->saveArray($subTranslation, $groupName, $locale, $parentKey ? $parentKey . '.' . $subKey : $subKey);
            } else {
                app(LanguageLineRepository::class)->createLanguageLineIfDoesntExist($groupName, $parentKey ? $parentKey . '.' . $subKey : $subKey, $locale, text: $subTranslation);
            }
        }
    }
}
