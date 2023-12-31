@extends('layouts.app')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="?" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="photos" class="col-form-label">Фотографии</label>
            <input id="photos" type="file" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                name="files[]" multiple required>
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Загрузить</button>
        </div>
    </form>

@endsection
