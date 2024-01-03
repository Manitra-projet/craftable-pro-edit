<?php

namespace CustomPackages\CustomApp\Http\Controllers\CraftableProUser;

use CustomPackages\CustomApp\Models\CraftableProUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class MyPasswordController extends Controller
{
    private CraftableProUser $craftableProUser;

    private function setUser(Request $request)
    {
        $this->craftableProUser = $request->user('custom-app');
    }

    public function edit(Request $request)
    {
        $this->setUser($request);

        return Inertia::render('CraftableProUser/Password/Edit', [
            'craftableProUser' => $this->craftableProUser,
        ]);
    }

    public function update(Request $request)
    {
        $this->setUser($request);

        $request->validate([
            'password' => ['required', 'confirmed', Password::default()],
        ]);

        $this->craftableProUser->update([
            'password' => Hash::make($request->get('password')),
        ]);

        return redirect()->back()->with(['message' => ___('custom-app', 'Password successfully updated')]);
    }
}
