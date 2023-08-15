<x-mail::message>
# Код подтверждения:
{{ $text }}

### Ваш номер: {{ $number }}

C Уважением,<br>
Администрация сайта,<br>
{{ config('app.name') }}
</x-mail::message>
