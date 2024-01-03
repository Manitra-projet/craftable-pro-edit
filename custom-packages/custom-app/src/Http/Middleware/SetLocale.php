<?php

namespace CustomPackages\CustomApp\Http\Middleware;

use CustomPackages\CustomApp\Settings\GeneralSettings;
use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if ($request->user('custom-app')) {
            app()->setLocale($request->user('custom-app')->locale);
        } else {
            app()->setLocale(app(GeneralSettings::class)->default_locale);
        }

        return $next($request);
    }
}
