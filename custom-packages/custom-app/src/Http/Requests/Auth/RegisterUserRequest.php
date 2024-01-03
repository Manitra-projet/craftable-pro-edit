<?php

namespace CustomPackages\CustomApp\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','string', 'email','max:255', Rule::unique('craftable_pro_users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'confirmed', Password::defaults()],
            'locale' => ['required','string','max:16'],
        ];
    }
}
