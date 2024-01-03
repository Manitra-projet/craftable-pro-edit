<?php

namespace CustomPackages\CustomApp\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDbConnectionCommand extends Command
{
    protected $signature = 'custom-app:test-db-connection';

    protected $description = 'Test connection into database';

    public function handle()
    {
        try {
            $this->components->task('Testing the database connection...', function () {
                DB::connection()->getPdo();
            });

            return 1;
        } catch (\Exception $e) {
            $this->output->error("Could not connect to the database.  Please check your configuration. Error: " . $e->getMessage());

            return 0;
        }
    }
}
