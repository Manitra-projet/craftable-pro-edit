<?php

namespace CustomPackages\CustomApp\Translations\Scanners\External;

use CustomPackages\CustomApp\Translations\Repositories\LanguageLineRepository;
use CustomPackages\CustomApp\Translations\Scanners\Contracts\ExternalScannerInterface;
use CustomPackages\CustomApp\Translations\Scanners\Contracts\ScannerInterface;
use Illuminate\Support\Facades\File;

class JsonScanner implements ExternalScannerInterface
{
    private array $scannedPaths;
    private string $group;

    public function scanAndSaveTranslations(): void
    {
        collect($this->scannedPaths)->each(function ($path) {
            if (! File::exists($path)) {
                return true;
            }

            collect(File::allFiles($path))->each(function ($file) {
                collect(json_decode(File::get($file)))->each(function ($translation, $key) {
                    $languageLine = app(LanguageLineRepository::class)->createLanguageLineIfDoesntExist($this->group, $key);
                });
            });
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
}
