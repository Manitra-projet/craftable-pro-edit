<?php

namespace CustomPackages\CustomApp\Translations\Scanners\Contracts;

interface ScannerInterface
{
    public function scanAndSaveTranslations(): void;

    public function addScannedPaths(array $scannedPaths): self;
}
