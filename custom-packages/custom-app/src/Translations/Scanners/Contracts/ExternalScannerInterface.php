<?php

namespace CustomPackages\CustomApp\Translations\Scanners\Contracts;

interface ExternalScannerInterface extends ScannerInterface
{
    public function setGroup(string $group): self;
}
