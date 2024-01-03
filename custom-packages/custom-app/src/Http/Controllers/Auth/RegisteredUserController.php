<?php

namespace CustomPackages\CustomApp\Http\Controllers\Auth;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Http\Requests\Auth\RegisterUserRequest;
use CustomPackages\CustomApp\Models\CraftableProUser;
use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Register', [
            'locales' => app(GeneralSettings::class)->available_locales,
            'defaultLocale' => app(GeneralSettings::class)->default_locale,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        /** @var CraftableProUser $user */
        $user = CraftableProUser::create($data);

        $user->assignRole(config('custom-app.self_registration.default_role'));

        config('custom-app.require_email_verified') ? event(new Registered($user)) : $user->markEmailAsVerified();

        Auth::guard('custom-app')->login($user);

        return redirect(app(GeneralSettings::class)->default_route);
    }
}
