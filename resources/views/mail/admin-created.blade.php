<x-mail::message>
# Welcome, {{ $user->name }}!

Your admin account has been created for {{ config('app.name') }}.

**Login Email:** {{ $user->email }}

**Temporary Password:** {{ $plainPassword }}

Please log in and change your password as soon as possible.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>
