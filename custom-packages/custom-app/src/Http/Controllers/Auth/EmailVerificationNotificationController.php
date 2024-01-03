<?php

namespace CustomPackages\CustomApp\Http\Controllers\Auth;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user('custom-app')->hasVerifiedEmail()) {
            return redirect()->intended(app(GeneralSettings::class)->default_route);
        }

        $request->user('custom-app')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
