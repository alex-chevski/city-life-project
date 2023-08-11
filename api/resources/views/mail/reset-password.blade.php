<x-mail::message>
# Письмо для сброса пароля

Сбросьте свой пароль кликнув по ссылке снизу

<x-mail::button :url="route('password.reset', ['token' => $user->verify_token])">
Сбросить пароль
</x-mail::button>

C Уважением,<br>
Администрация сайта,<br>
{{ config('app.name') }}
</x-mail::message>
