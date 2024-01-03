<?php

namespace CustomPackages\CustomApp\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;

class InstallCommand extends Command
{
    use InteractsWithIO;

    public $signature = 'custom-app:install      {--overwrite        : Overwrite files}
                                                    {--no-user          : Don\'t generate the default admin user}
                                                    {--no-migrate       : Don\'t run migrations}
                                                    {--without-logger   : Don\'t install advanced logger package}';


    public $description = 'Install CraftablePro';

    protected $assetsNamespace = 'custom-app';

    public function handle(): int
    {
        $this->components->info("Craftable PRO installation started");

        if ($this->call("custom-app:test-db-connection") === 0) {
            $this->components->error("In order to install Craftable PRO, please check and configure the database connection");

            return false;
        }

        $this->updateNodePackages();

        $this->installFrontend();

        $this->installRoutes();

        $this->installAdvancedLoggerPackage();

        $this->newLine();

        $this->components->info("Publishing migrations...");

        $this->call('vendor:publish', [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
            '--tag' => "permission-migrations",
        ]);

        $this->call('vendor:publish', [
            '--provider' => "Spatie\LaravelSettings\LaravelSettingsServiceProvider",
            '--tag' => "settings",
        ]);

        // check migration is created, because Spatie uses old-way of detecting if migration exists and it doesn't work correctly
        if (count(glob(base_path('database/migrations/*create_settings_table.php'))) === 0) {
            $this->call('vendor:publish', [
                '--provider' => "Spatie\LaravelSettings\LaravelSettingsServiceProvider",
                '--tag' => "migrations",
            ]);
        }

        $this->call('vendor:publish', [
            '--provider' => "CustomPackages\CustomApp\CustomAppServiceProvider",
            '--tag' => "custom-app-migrations",
        ]);

        if (! $this->option('no-migrate')) {
            $this->components->info("Running migrations...");
            $this->call('migrate');
        }

        $this->scanAndPublishTranslations();

        $this->newLine();

        $this->components->info("Craftable PRO successfully installed");

        if (! $this->option('no-user')) {
            $this->call(
                'custom-app:create-admin-user',
                [
                    '--no-interaction' => $this->option('no-interaction'),
                ]
            );
        }

        $this->components->warn('Don\'t forget to execute the "npm install && npm run custom-app:dev" command to build newly published assets.');

        return self::SUCCESS;
    }

    /**
     * Update the "package.json" file.
     *
     * @param callable $callback
     * @param bool $dev
     * @return void
     */
    protected function editPackageJson(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            $this->components->twoColumnDetail("File package.json not found, was not able to install additional packages", '<fg=red;options=bold>ERROR</>');

            return;
        }

        $this->components->task("Updating package.json", function () use ($callback, $dev) {
            $configurationKey = $dev ? 'devDependencies' : 'dependencies';

            $packages = json_decode(file_get_contents(base_path('package.json')), true);

            $packages['scripts'] = $packages['scripts'] + [
                "custom-app:dev" => "vite --config custom-app.vite.config.js",
                "custom-app:build" => "vite build --config custom-app.vite.config.js",
            ];

            $packages[$configurationKey] = $callback(
                array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
                $configurationKey
            );

            ksort($packages[$configurationKey]);

            file_put_contents(
                base_path('package.json'),
                json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
            );
        });
    }

    public function updateNodePackages(): void
    {
        $this->editPackageJson(function ($packages) {
            // TODO: check the versions of the packages
            return [
                '@brackets/vue-toastification' => '^2.0.2',
                '@formkit/auto-animate' => '^0.7.0',
                '@headlessui/vue' => '^1.7.4',
                '@headlessui-float/vue' => '^0.10.1',
                '@heroicons/vue' => '^2.0.13',
                '@inertiajs/vue3' => '^1.0.0',
                '@tailwindcss/forms' => '^0.5.3',
                '@tailwindcss/typography' => '^0.5.7',
                '@tiptap/extension-image' => '^2.0.0',
                '@tiptap/extension-link' => '^2.0.0',
                '@tiptap/extension-text-align' => '^2.0.0',
                '@tiptap/extension-underline' => '^2.0.0',
                '@tiptap/extension-youtube' => '^2.0.0',
                '@tiptap/pm' => '^2.0.0',
                '@tiptap/starter-kit' => '^2.0.0',
                '@tiptap/vue-3' => '^2.0.0',
                '@types/vue-cropperjs' => '^4.1.2',
                '@types/ziggy-js' => '^1.3.2',
                '@vitejs/plugin-vue' => '^4.0.0',
                '@vue/compiler-sfc' => '^3.2.31',
                '@vueform/multiselect' => '^2.5.6',
                'autoprefixer' => '^10.4.2',
                'cropperjs' => '^1.5.13',
                'laravel-vite-plugin' => '^0.7.0',
                'laravel-vue-i18n' => '^1.4.4',
                'lodash' => '^4.17.19',
                'postcss' => '^8.4.6',
                'postcss-import' => '^14.1.0',
                'tailwindcss' => '^3.3.1',
                'uuid' => '^9.0.0',
                'vite' => '^4.0.0',
                'vue' => '^3.2.31',
                'vue-cropperjs' => '^5.0.0',
                'v-calendar' => '^3.0.3',
                'dayjs' => '^1.11.5',
            ] + $packages;
        });
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Look if file contains a given string.
     *
     * @param string $path
     * @param string $search
     * @return bool
     */
    protected function fileContains($path, $search)
    {
        return strpos(file_get_contents($path), $search) !== false;
    }

    /**
     * @return void
     */
    protected function installFrontend(): void
    {
        $this->components->task("Publishing Craftable PRO root template", function () {
            $this->copyIfNotExistOrOverwrite(__DIR__ . '/../../stubs/resources/views/custom-app.blade.php', resource_path('views/custom-app.blade.php'));
        });

        $this->components->task("Publishing Craftable PRO assets", function () {
            (new Filesystem())->ensureDirectoryExists(resource_path("js/{$this->assetsNamespace}"));
            (new Filesystem())->ensureDirectoryExists(resource_path("css"));

            if ($this->option('overwrite')) {
                (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js', resource_path("js/{$this->assetsNamespace}"));
                (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/css', resource_path("css"));
            } else {
                $this->copyIfNotExistFile(__DIR__ . '/../../stubs/resources/js', resource_path("js/{$this->assetsNamespace}"));
                $this->copyIfNotExistFile(__DIR__ . '/../../stubs/resources/css', resource_path("css"));
            }
        });

        $generateTSConfig = true;
        if (file_exists(base_path('tsconfig.json')) || $this->option('overwrite')) {
            $replaceTsConfig = ! $this->option('no-interaction') ? $this->components->choice(
                question: "It looks like you already have a TS config in this project. Should I overwrite it?",
                choices: ['yes', 'no'],
                default: 'no',
            ) : "no";

            if ($replaceTsConfig === "no") {
                $generateTSConfig = false;
                $this->printSkipped("Okay, TS config was not replaced. You should update it manually based on the documentation.");
            }
        }

        $this->components->task("Installing and configuring Tailwind and Vite", function () use ($generateTSConfig) {
            $this->copyIfNotExistOrOverwrite(__DIR__ . '/../../stubs/custom-app.tailwind.config.js', base_path('custom-app.tailwind.config.js'));
            $this->copyIfNotExistOrOverwrite(__DIR__ . '/../../stubs/custom-app.vite.config.js', base_path('custom-app.vite.config.js'));

            if ($generateTSConfig) {
                copy(__DIR__ . '/../../stubs/tsconfig.json', base_path('tsconfig.json'));
            }
        });
    }

    /**
     * @return void
     */
    protected function installRoutes(): void
    {
        if (! Route::has('custom-app.home')) {
            $this->components->task("Registering Craftable PRO routes");
            file_put_contents(base_path('routes/web.php'), PHP_EOL . "\n\nRoute::craftablePro('admin');", FILE_APPEND);
        } else {
            $this->printSkipped("Craftable PRO routes are already installed");
        }
    }

    /**
     * @return void
     */
    protected function scanAndPublishTranslations(): void
    {
        $this->components->info("Publishing translations");

        $this->components->task("Generate translations for permissions", function () {
            $this->call('custom-app:generate-permission-translations');
            $this->call('custom-app:generate-locale-translations');
        });

        $this->components->task("Scan codebase used for translations", function () {
            $this->call('custom-app:scan-translations');
        });

        $this->components->task("Publish translations (generate JSON files)", function () {
            $this->call('custom-app:publish-translations');
        });
    }

    protected function installAdvancedLoggerPackage()
    {
        if (! $this->option('without-logger')) {
            $this->newLine();
            $this->components->info("Craftable PRO supports the package Request Logger that automatically logs all incoming requests with handy information for debugging problems.");
            $install = ! $this->option('no-interaction') ? $this->components->choice(
                question: "Would you like to install Requests Logger? ",
                choices: ['yes', 'no'],
                default: 'no'
            ) : "no";

            if ($install === 'yes') {
                $this->call('custom-app:install-advanced-logger');
            }
        }
    }

    /**
     * Check in path all files is exist and copy not exists files
     *
     * @param $path
     * @param $destination
     * @return void
     */
    private function copyIfNotExistFile($path, $destination): void
    {
        $items = new \FilesystemIterator($path);

        collect($items)->each(function ($item) use ($path, $destination) {
            $base_name = $item->getBaseName();
            $destination_file_path = "$destination/$base_name";
            $file_path = "$path/$base_name";

            if ($item->isDir()) {
                // not exist directory create then
                if (! (new Filesystem())->exists($destination_file_path)) {
                    (new Filesystem())->ensureDirectoryExists($destination_file_path);
                }

                $this->copyIfNotExistFile(path: $file_path, destination: $destination_file_path);
            }

            if (! file_exists($destination_file_path)) {
                (new Filesystem())->copy(path: $file_path, target: $destination_file_path);

                return true;
            }
        });
    }

    /**
     * check file is exist or called option overwrite and copy file
     *
     * @param $path
     * @param $destination
     * @return void
     */
    private function copyIfNotExistOrOverwrite($path, $destination): void
    {
        if (! file_exists($destination) || $this->option('overwrite')) {
            copy($path, $destination);
        }
    }

    /**
     * @return void
     */
    protected function printSkipped(string $text): void
    {
        $this->components->twoColumnDetail($text, '<fg=yellow;options=bold>SKIPPED</>');
    }
}
