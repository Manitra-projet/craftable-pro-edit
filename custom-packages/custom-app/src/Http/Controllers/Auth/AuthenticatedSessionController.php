<?php

namespace CustomPackages\CustomApp\Http\Controllers\Auth;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Http\Requests\Auth\LoginRequest;
use CustomPackages\CustomApp\Models\CraftableProUser;
use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request)
    {
        $data = $request->validated();
        $user = CraftableProUser::whereEmail($data['email'])->first();

        if ($user?->wasInvited()) {
            return Inertia::location(route("custom-app.invite-user.create", $data['email']));
        }

        $request->authenticate();

        $request->session()->regenerate();
        $routeName = app(GeneralSettings::class)->default_route;
        $redirectUrl = url($routeName);

        return Inertia::location($redirectUrl);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('custom-app')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
