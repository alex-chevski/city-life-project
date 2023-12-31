@extends('layouts.app')

@section('content')
    @include('admin.pages._nav')

    <form method="POST" action="{{ route('admin.pages.store') }}">
        @csrf

        <div class="form-group">
            <label for="title" class="col-form-label">Заголовок</label>
            <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                value="{{ old('title') }}" required>
            @if ($errors->has('title'))
                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="menu_title" class="col-form-label">Заголовок в шапке</label>
            <input id="menu_title" class="form-control{{ $errors->has('menu_title') ? ' is-invalid' : '' }}"
                name="menu_title" value="{{ old('menu_title') }}">
            @if ($errors->has('menu_title'))
                <span class="invalid-feedback"><strong>{{ $errors->first('menu_title') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="slug" class="col-form-label">Отображение адреса</label>
            <input id="slug" type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                name="slug" value="{{ old('slug') }}" required>
            @if ($errors->has('slug'))
                <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="parent" class="col-form-label">Parent</label>
            <select id="parent" class="form-control{{ $errors->has('parent') ? ' is-invalid' : '' }}" name="parent">
                <option value=""></option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}"{{ $parent->id == old('parent') ? ' selected' : '' }}>
                        @for ($i = 0; $i < $parent->depth; $i++)
                            &mdash;
                        @endfor
                        {{ $parent->title }}
                    </option>
                @endforeach;
            </select>
            @if ($errors->has('parent'))
                <span class="invalid-feedback"><strong>{{ $errors->first('parent') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="content" class="col-form-label">Основная часть</label>
            <textarea id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }} summernote"
                data-image-url="{{ route('admin.ajax.upload.image') }}" name="content" rows="10" required>{{ old('content') }}</textarea>
            @if ($errors->has('content'))
                <span class="invalid-feedback"><strong>{{ $errors->first('content') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="description" class="col-form-label">Описание</label>
            <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                name="description" rows="3">{{ old('description') }}</textarea>
            @if ($errors->has('description'))
                <span class="invalid-feedback"><strong>{{ $errors->first('description') }}</strong></span>
            @endif
        </div>

        <div class="form-group mt-2">
            <button type="submit" class="btn btn-primary">Создать</button>
        </div>
    </form>
@endsection
