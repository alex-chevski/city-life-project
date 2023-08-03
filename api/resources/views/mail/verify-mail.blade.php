<x-mail::message>
# Подтверждение почты

Пожалуйста подтвердите свою почту, кликнув по ссылке снизу

<x-mail::button :url="route('register.verify', ['token' => $user->verify_token])">
Потвердить почту
</x-mail::button>

C Уважением,<br>
Администрация сайта,<br>
{{ config('app.name') }}
</x-mail::message>
