@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <p><a href="{{ route('admin.users.create') }}" class="btn btn-success">Добавить пользователя</a></p>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Электронная почта</th>
            <th>Статус</th>
            <th>Роль</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>

                <td>
                    @if ($user->isWait())
                        <span class="badge text-bg-secondary">Waiting</span>
                    @endif
                    @if ($user->isActive())
                        <span class="badge text-bg-primary">Active</span>
                    @endif
                </td>

                <td>
                    @if ($user->isAdmin())
                        <span class="badge text-bg-danger text-light">Admin</span>
                    @else
                        <span class="badge text-bg-secondary">User</span>
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{ $users->links() }}
@endsection
