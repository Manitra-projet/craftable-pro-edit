<?php

namespace CustomPackages\CustomApp\Commands;

use CustomPackages\CustomApp\Translations\TranslationsProcessor;
use Illuminate\Console\Command;

class PublishTranslationsCommand extends Command
{
    public $signature = 'custom-app:publish-translations';

    public $description = 'Publish translations';

    public function __construct(private TranslationsProcessor $processor)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->processor->publishTranslations();
    }
}
