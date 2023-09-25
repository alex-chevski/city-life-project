@extends('layouts.app')

@section('content')
    @include('cabinet.tickets._nav')

    <form method="POST" action="{{ route('cabinet.tickets.store') }}">
        @csrf

        <div class="form-group">
            <label for="subject" class="col-form-label">Тема Обращения</label>
            <input id="subject" class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" name="subject"
                value="{{ old('subject') }}" required>
            @if ($errors->has('subject'))
                <span class="invalid-feedback"><strong>{{ $errors->first('subject') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="content" class="col-form-label">Опишите вашу проблему</label>
            <textarea id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content"
                rows="10" required>{{ old('content') }}</textarea>
            @if ($errors->has('content'))
                <span class="invalid-feedback"><strong>{{ $errors->first('content') }}</strong></span>
            @endif
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </form>
@endsection
