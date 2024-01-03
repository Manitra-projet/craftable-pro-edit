<?php

namespace CustomPackages\CustomApp\Commands;

use CustomPackages\CustomApp\Translations\TranslationsProcessor;
use Illuminate\Console\Command;

class ScanAndSaveTranslationsCommand extends Command
{
    public $signature = 'custom-app:scan-translations';

    public $description = 'Scan translations';

    public function __construct(private TranslationsProcessor $processor)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->processor->scanTranslations();
    }
}
