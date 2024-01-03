<?php

namespace CustomPackages\CustomApp\Http\Controllers\Auth;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        return $request->user('custom-app')->hasVerifiedEmail()
                    ? redirect()->intended(app(GeneralSettings::class)->default_route)
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
