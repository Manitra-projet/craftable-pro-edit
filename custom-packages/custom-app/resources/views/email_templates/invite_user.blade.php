<x-mail::message>

# {{ ___('custom-app', 'Invite user') }}

You were invited by user {{ $userFullName }} to join {{ config('app.name') }}.

Please follow the link bellow to create your account.

<x-mail::button :url="route('custom-app.invite-user.create', $email)">
{{ ___('custom-app', 'Sign in') }}
</x-mail::button>

</x-mail::message>
