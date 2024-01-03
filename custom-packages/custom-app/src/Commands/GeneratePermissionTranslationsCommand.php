<?php

namespace CustomPackages\CustomApp\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class GeneratePermissionTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-app:generate-permission-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permission translations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! File::exists(resource_path('translations'))) {
            File::makeDirectory(resource_path('translations'));
        }

        if (! File::exists(resource_path('translations/permissions'))) {
            File::makeDirectory(resource_path('translations/permissions'));
        }

        $permissions = collect(Permission::all()->map->name)->mapWithKeys(function ($permission) {
            $name = "";
            $permissionName = collect();

            collect(explode(".", $permission))->each(function ($value, $key) use (&$name, &$permissionName) {
                $name .= $key == 0 ? $value : ".$value";

                $permissionName->put($name, $name);
            });

            return $permissionName->all();
        });

        // save permission translation keys
        File::put(resource_path('translations/permissions') . '/permission_translations.json', $permissions->toJson());

        $this->generateAndSaveTranslatedPermissions($permissions);

        return Command::SUCCESS;
    }

    /**
     * Generate translated permissions and save
     *
     * @param Collection $permissions
     * @return void
     */
    protected function generateAndSaveTranslatedPermissions(Collection $permissions)
    {
        $permission_file_path = __DIR__."/../../resources/translations/permissions_en.json";
        $translatedPermissions = collect(json_decode(File::get($permission_file_path), true));

        $permissions->each(function ($permission) use (&$translatedPermissions) {
            if (! $translatedPermissions->has("permissions.$permission")) {
                $translatedPermissions->put("permissions.$permission", $this->translateWithKey($permission));
            }
        });

        File::put($permission_file_path, $translatedPermissions->toJson());
    }

    protected function translateWithKey(string $key)
    {
        $fullName = Str::replace("custom-app.", "", $key);
        if (strpos($fullName, ".") === false) {
            return Str::headline(Str::plural($fullName));
        }
        $explodedName = explode(".", $fullName);
        $name = Str::headline($explodedName[0]);

        switch ($explodedName[1]) {
            case "index":
                return "List of ".Str::plural($name);
            case "create":
                return "Create $name";
            case "store":
                return "Store $name";
            case "edit":
                return "Edit $name";
            case "update":
                return "Update $name";
            case "destroy":
                return "Delete $name";
            default:
                return $name;
        }
    }
}
