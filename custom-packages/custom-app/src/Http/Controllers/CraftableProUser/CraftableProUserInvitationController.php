<?php

namespace CustomPackages\CustomApp\Http\Controllers\CraftableProUser;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Http\Requests\Auth\InviteUserRequest;
use CustomPackages\CustomApp\Http\Requests\Auth\InviteUserStoreRequest;
use CustomPackages\CustomApp\Mail\InvitationUserMail;
use CustomPackages\CustomApp\Models\CraftableProUser;
use CustomPackages\CustomApp\Settings\GeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CraftableProUserInvitationController extends Controller
{
    public function inviteUser(InviteUserRequest $request)
    {
        $data = $request->validated();

        $user = CraftableProUser::create([
            'email' => $data['email'],
            'password' => bcrypt(Str::random(12)),
            'locale' => app(GeneralSettings::class)->default_locale,
            'active' => false,
            'invitation_sent_at' => now(),
        ])->assignRole($data['role_id']);

        static::sendInvitation(
            email: $data['email'],
            userFullName: Auth::user()->first_name . " " . Auth::user()->last_name
        );

        return redirect()->back()->with(['message' => ___("custom-app", "User was succesfully invited.")]);
    }

    public function createInviteAcceptationUser($email)
    {
        $user = CraftableProUser::whereEmail($email)->firstOrFail();

        if (! $user->wasInvited()) {
            return redirect()->route("custom-app.login");
        }

        return Inertia::render('Auth/InviteUser', [
            'email' => $email,
        ]);
    }

    public function storeInviteAcceptationUser(InviteUserStoreRequest $request)
    {
        $data = $request->validated();
        $user = CraftableProUser::whereEmail($data['email'])->first();
        $data['password'] = bcrypt($data['password']);
        $user->update($data + ['active' => true, 'invitation_accepted_at' => now()]);
        $user->markEmailAsVerified();

        return redirect()->route('custom-app.login');
    }

    public static function sendInvitation(string $email, string $userFullName)
    {
        Mail::to($email)->send(new InvitationUserMail([
            'email' => $email,
            'userFullName' => $userFullName,
        ]));
    }
}
