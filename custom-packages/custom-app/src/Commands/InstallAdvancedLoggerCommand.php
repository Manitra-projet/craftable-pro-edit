<?php

namespace CustomPackages\CustomApp\Commands;

use Illuminate\Console\Command;

class InstallAdvancedLoggerCommand extends Command
{
    protected $signature = 'custom-app:install-advanced-logger';

    protected $description = 'Install advanced logger package';

    public function handle()
    {
        $this->components->info('Installing Advanced logger...');

        // TODO: check and get a more conceptual solution
        shell_exec("composer require brackets/advanced-logger");

        $this->call('vendor:publish', [
            '--provider' => 'Brackets\AdvancedLogger\AdvancedLoggerServiceProvider',
        ]);

        $this->components->info('Advanced logger is succesfully installed');
    }
}
