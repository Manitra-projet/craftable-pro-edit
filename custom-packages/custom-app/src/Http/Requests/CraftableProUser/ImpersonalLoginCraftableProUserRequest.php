<?php

namespace CustomPackages\CustomApp\Http\Requests\CraftableProUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ImpersonalLoginCraftableProUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('custom-app.custom-app-user.impersonal-login', $this->craftableProUser);
    }

    public function rules(): array
    {
        return [];
    }
}
