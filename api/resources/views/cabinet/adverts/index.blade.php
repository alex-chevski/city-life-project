@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.home') }}">Доска объявлений</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('cabinet.adverts.index') }}">Мои Объявления</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.profile.home') }}">Мой Профиль</a></li>
    </ul>
@endsection
