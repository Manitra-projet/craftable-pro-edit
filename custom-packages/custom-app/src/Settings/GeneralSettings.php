<?php

namespace CustomPackages\CustomApp\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public array $available_locales;

    public string $default_locale;

    public string $default_route;

    public static function group(): string
    {
        return 'general';
    }
}
