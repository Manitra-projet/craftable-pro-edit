<?php

namespace CustomPackages\CustomApp\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', Rule::unique('craftable_pro_users', 'email')->whereNull('deleted_at')],
            'role_id' => ['required', 'string'],
        ];
    }
}
