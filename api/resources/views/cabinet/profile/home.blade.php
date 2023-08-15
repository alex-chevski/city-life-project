@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <div class="mb-3">
        <a href="{{ route('cabinet.profile.edit') }}" class="btn btn-primary">Настроить профиль</a>
    </div>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>Имя</th><td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Фамилия</th><td>{{ $user->last_name }}</td>
        </tr>
        <tr>
            <th>Электронная почта</th><td>{{ $user->email }}</td>
        </tr>

        <tr>
            <th>Телефон</th><td>
                @if ($user->phone)
                    {{ $user->phone }}
                    @if (!$user->isPhoneVerified())
                        <i>(не подтвержден)</i><br />
                        <form method="POST" action="{{ route('cabinet.profile.phone') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Подтвердить</button>
                        </form>
                    @endif
                @endif
            </td>
        </tr>
        </tbody>
    </table>
@endsection
