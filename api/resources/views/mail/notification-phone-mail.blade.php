<x-mail::message>
    {{ $text }}

    Ваш номер: {{ $number }}

    C Уважением,
    Администрация сайта,
    {{ config('app.name') }}
</x-mail::message>
