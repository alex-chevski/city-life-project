@extends('layouts.app')

@section('content')
    @include('cabinet.tickets._nav')

    <p><a href="{{ route('cabinet.tickets.create') }}" class="btn btn-success">Написать в Тех Поддержку</a></p>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Создано</th>
                <th>Последнее изменение</th>
                <th>Тема</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->created_at }}</td>
                    <td>{{ $ticket->updated_at }}</td>
                    <td><a href="{{ route('cabinet.tickets.show', $ticket) }}" target="_blank">{{ $ticket->subject }}</a></td>
                    <td>
                        @if ($ticket->isOpen())
                            <span class="badge bg-danger">Открыто</span>
                        @elseif ($ticket->isApproved())
                            <span class="badge bg-primary">Принято</span>
                        @elseif ($ticket->isClosed())
                            <span class="badge bg-secondary">Закрыто</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $tickets->links() }}
@endsection
