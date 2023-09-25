@extends('layouts.app')

@section('content')
    @include('cabinet.tickets._nav')

    <div class="d-flex flex-row mb-3">
        @if ($ticket->canBeRemoved())
            <form method="POST" action="{{ route('cabinet.tickets.destroy', $ticket) }}" class="me-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        @endif
    </div>

    <div class="row">
        <div class="col-md-7">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>#</th>
                        <td>{{ $ticket->id }}</td>
                    </tr>
                    <tr>
                        <th>Создано</th>
                        <td>{{ $ticket->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Дата последнего изменения</th>
                        <td>{{ $ticket->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>Статус</th>
                        <td>
                            @if ($ticket->isOpen())
                                <span class="badge bg-danger">Открыто</span>
                            @elseif ($ticket->isApproved())
                                <span class="badge bg-primary">Принято</span>
                            @elseif ($ticket->isClosed())
                                <span class="badge b-secondary">Закрыто</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Пользователь</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ticket->statuses()->orderBy('id')->with('user')->get() as $status)
                        <tr>
                            <td>{{ $status->created_at }}</td>
                            <td>{{ $status->user->name }}</td>
                            <td>
                                @if ($status->isOpen())
                                    <span class="badge bg-danger">Открыто</span>
                                @elseif ($status->isApproved())
                                    <span class="badge bg-primary">Принято</span>
                                @elseif ($status->isClosed())
                                    <span class="badge bg-secondary">Закрыто</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            {{ $ticket->subject }}
        </div>
        <div class="card-body">
            {!! nl2br(e($ticket->content)) !!}
        </div>
    </div>

    @foreach ($ticket->messages()->orderBy('id')->with('user')->get() as $message)
        <div class="card mb-3">
            <div class="card-header">
                {{ $message->created_at }} by {{ $message->user->name }}
            </div>
            <div class="card-body">
                {!! nl2br(e($message->message)) !!}
            </div>
        </div>
    @endforeach

    @if ($ticket->allowsMessages())
        <form method="POST" action="{{ route('cabinet.tickets.message', $ticket) }}">
            @csrf

            <div class="form-group">
                <textarea class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" name="message" rows="3"
                    required>{{ old('message') }}</textarea>
                @if ($errors->has('message'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('message') }}</strong></span>
                @endif
            </div>

            <div class="form-group mb-0 mt-3">
                <button type="submit" class="btn btn-primary">Отправить сообщение</button>
            </div>
        </form>
    @endif
@endsection
