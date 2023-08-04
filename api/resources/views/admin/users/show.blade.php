@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary mr-1">Изменить пользователя</a>

        <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="mr-1">
            @csrf
            <button class="btn btn-warning me-2 ms-2">Изменить статус</button>
        </form>

        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mr-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>

    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th><td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Имя</th><td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Электронная почта</th><td>{{ $user->email }}</td>
        </tr>

        <tr>
            <th>Статус</th>
            <td>
                @if ($user->isWait())
                    <span class="badge text-bg-secondary">Waiting</span>
                @endif
                @if ($user->isActive())
                    <span class="badge text-bg-primary">Active</span>
                @endif
            </td>
        </tr>


        <tr>
            <th>Роль</th>
            <td>
                @if ($user->isAdmin())
                    <span class="badge text-bg-danger text-light">Admin</span>
                @else
                    <span class="badge text-bg-secondary">User</span>
                @endif
            </td>
        </tr>


        <tbody>
        </tbody>
    </table>
@endsection
