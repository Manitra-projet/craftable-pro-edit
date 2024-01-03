<?php

namespace CustomPackages\CustomApp\Commands\Trait;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait BaseCrudGeneratorTrait
{
    /**
     * Append web route file with content.
     *
     * @param $path
     * @param $content
     * @param $defaultContent
     * @return bool
     */
    protected function appendIfNotAlreadyAppended($path, $content, $defaultContent = "<?php" . PHP_EOL . PHP_EOL)
    {
        if (! $this->files->exists($path)) {
            $this->makeDirectory($path);
            $this->files->put($path, $defaultContent . $content);
        } elseif (! $this->alreadyAppended($path, $content)) {
            $this->files->append($path, $content);
        } else {
            return false;
        }

        return true;
    }

    /**
     * Check if alredy appened content in web route file.
     *
     * @param $path
     * @param $content
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function alreadyAppended($path, $content)
    {
        if (strpos($this->files->get($path), $content) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Helper function check and create directory for path.
     *
     * @param $path
     * @return mixed
     */
    protected function makeDirectory($path): mixed
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Return vue template file for current view in param.
     *
     * @param $view
     * @param $content
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getVueFile($view, $content = true)
    {
        $stub_path = __DIR__ . '/../../../resources/views/generator/views/' . "{$view}.vue";

        return $content ? $this->files->get($stub_path) : $stub_path;
    }

    /**
     * Return typescript file types.d.ts
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getTypeScriptFile()
    {
        $path = __DIR__ . "/../../../resources/views/generator/views/types.d.ts";

        return $this->files->get($path);
    }

    /**
     * Return Controller path.
     *
     * @param $name
     * @return string
     */
    protected function getControllerPath($name): string
    {
        return $this->makeDirectory(app_path($this->getNamespacePath($this->controllerNamespace) . "{$name}.php"));
    }

    /**
     * Return model path.
     *
     * @param $name
     * @return string
     */
    protected function getModelPath(string $name): string
    {
        return $this->makeDirectory(app_path($this->getNamespacePath($this->modelNamespace) . "{$name}.php"));
    }

    /**
     * Return reqeuest path.
     *
     * @param string $name
     * @return string
     */
    private function getRequestPath(string $name): string
    {
        return $this->makeDirectory(app_path($this->getNamespacePath($this->requestNamespace) . "{$name}.php"));
    }

    /**
     * Return resource vue path.
     *
     * @param string $name
     * @param string $fileName
     * @return string
     */
    private function getResourcesPath(string $name, string $fileName): string
    {
        return $this->makeDirectory(resource_path("js/custom-app/Pages/{$name}/{$fileName}.vue"));
    }

    /**
     * Return migration path and get migration file name.
     *
     * @param string $name
     * @return string
     */
    private function getMigrationPath(string $name): string
    {
        $datetime = str_replace(["-", " ", ":"], ["_", "_", ""], Carbon::now()->toDateTimeString());
        $this->migration_name = $datetime . "_add_permissions_to_{$name}.php";

        return database_path("migrations/" . $this->migration_name);
    }

    private function getExportPath(string $name): string
    {
        return $this->makeDirectory(app_path($this->getNamespacePath($this->exportNamespace) . "{$name}.php"));
    }

    /**
     * Get the path from namespace.
     *
     * @param $namespace
     * @return string
     */
    private function getNamespacePath($namespace): string
    {
        $str = Str::start(Str::finish(Str::after($namespace, 'App'), '\\'), '\\');

        return str_replace('\\', '/', $str);
    }
}
