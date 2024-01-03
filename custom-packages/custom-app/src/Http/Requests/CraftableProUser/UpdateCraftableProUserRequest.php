<?php

namespace CustomPackages\CustomApp\Http\Requests\CraftableProUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateCraftableProUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('custom-app.custom-app-user.edit', $this->craftableProUser);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'email' => ['required', 'email', Rule::unique('craftable_pro_users', 'email')->whereNull('deleted_at')->ignore($this->craftableProUser->id)],
            'password' => ['sometimes', 'confirmed', 'min:7', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'locale' => ['sometimes', 'required', 'string'],
            'role_id' => ['sometimes', 'required', 'exists:roles,id'],
            'active' => ['sometimes', Rule::requiredIf(config('custom-app.allow_only_active_users_login')), 'boolean'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (array_key_exists('password', $validated)) {
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }
        }

        return $validated;
    }
}
