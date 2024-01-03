<?php

namespace CustomPackages\CustomApp\Http\Controllers\Auth;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Models\CraftableProUser;
use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @return \Inertia\Response
     */
    public function show()
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        /** @var CraftableProUser $user */
        $user = $request->user('custom-app');

        if (! Auth::guard('custom-app')->validate([
            'email' => $user->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(app(GeneralSettings::class)->default_route);
    }
}
