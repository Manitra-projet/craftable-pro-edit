<?php

namespace CustomPackages\CustomApp\Http\Middleware;

use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;

class CustomAppHandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'custom-app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $settings = app(GeneralSettings::class);

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => fn () => $request->user('custom-app') ? $request->user('custom-app')->only('id', 'first_name', 'last_name', 'email', 'initials', 'avatar_url', 'locale') : null,
                'permissions' => fn () => $request->user('custom-app') ? $request->user('custom-app')->getAllPermissions()->pluck('name') : [],
            ],
            'message' => fn () => $request->session()->get('message'),
            'sort' => fn () => $request->get('sort'),
            'filter' => fn () => $request->get('filter'),
            'csrf_token' => csrf_token(),
            'config' => [
                'craftable_pro' => [
                    'track_user_last_active_time' => config('custom-app.track_user_last_active_time', false),
                ],
            ],
            'settings' => [
                'available_locales' => $settings->available_locales,
            ],
        ]);
    }
}
