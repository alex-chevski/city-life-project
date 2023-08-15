@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <form method="POST" action="{{ route('cabinet.profile.phone.verify') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="token" class="col-form-label">Смс код</label>
            <input id="token" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token" value="{{ old('token') }}" required>
            @if ($errors->has('token'))
                <span class="invalid-feedback"><strong>{{ $errors->first('token') }}</strong></span>
            @endif
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Подтвердить</button>
        </div>
    </form>
@endsection
