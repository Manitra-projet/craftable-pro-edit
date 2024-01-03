<?php

use CustomPackages\CustomApp\Settings\GeneralSettings;

if (! function_exists("___")) {
    function ___($group, $key, $params = [], $locale = null)
    {
        return trans($group . '.' . $key, $params, $locale);
    }

    function ___ch($group, $key, $number, $params = [], $locale = null)
    {
        return trans_choice($group . '.' . $key, $number, $params, $locale);
    }
}

if (! function_exists('getAvailableLocalesTranslated')) {
    function getAvailableLocalesTranslated()
    {
        return collect(app(GeneralSettings::class)->available_locales)->map(function ($locale) {
            return [
                "key" => $locale,
                "value" => trans("locales.custom-app.$locale"),
            ];
        })->all();
    }
}
