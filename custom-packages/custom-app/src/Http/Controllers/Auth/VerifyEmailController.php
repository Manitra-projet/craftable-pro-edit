<?php

namespace CustomPackages\CustomApp\Http\Controllers\Auth;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user('custom-app')->hasVerifiedEmail()) {
            return redirect()->intended(app(GeneralSettings::class)->default_route . '?verified=1');
        }

        if ($request->user('custom-app')->markEmailAsVerified()) {
            event(new Verified($request->user('custom-app')));
        }

        return redirect()->intended(app(GeneralSettings::class)->default_route . '?verified=1');
    }
}
