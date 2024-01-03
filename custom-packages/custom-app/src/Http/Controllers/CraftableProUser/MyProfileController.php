<?php

namespace CustomPackages\CustomApp\Http\Controllers\CraftableProUser;

use CustomPackages\CustomApp\Models\CraftableProUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class MyProfileController extends Controller
{
    private CraftableProUser $craftableProUser;

    private function setUser(Request $request)
    {
        $this->craftableProUser = $request->user('custom-app');
    }

    public function edit(Request $request)
    {
        $this->setUser($request);

        return Inertia::render('CraftableProUser/Profile/Edit', [
            'craftableProUser' => $this->craftableProUser,
            'locales' => getAvailableLocalesTranslated(),
        ]);
    }

    public function update(Request $request)
    {
        $this->setUser($request);

        $validated = $request->validate([
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'locale' => ['sometimes', 'string'],
        ]);

        $this->craftableProUser->update($validated);

        return redirect()->back()->with(['message' => ___('custom-app', 'Profile successfully updated')]);
    }
}
