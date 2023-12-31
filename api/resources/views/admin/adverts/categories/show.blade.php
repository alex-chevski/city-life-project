@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')

    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.adverts.categories.edit', $category) }}" class="btn btn-primary me-1">Изменить</a>
        <form method="POST" action="{{ route('admin.adverts.categories.destroy', $category) }}" class="me-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th><td>{{ $category->id }}</td>
        </tr>
        <tr>
            <th>Название</th><td>{{ $category->name }}</td>
        </tr>
        <tr>
            <th>Slug</th><td>{{ $category->slug }}</td>
        </tr>
        <tbody>
        </tbody>
    </table>

    <p><a href="{{ route('admin.adverts.categories.attributes.create', $category) }}" class="btn btn-success">Добавить аттрибут</a></p>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Sort</th>
            <th>Название</th>
            <th>Slug</th>
            <th>Required</th>
        </tr>
        </thead>
        <tbody>

        <tr><th colspan="4">Родительские аттрибуты</th></tr>

        @forelse ($parentAttributes as $attribute)
            <tr>
                <td>{{ $attribute->sort }}</td>
                <td>{{ $attribute->name }}</td>
                <td>{{ $attribute->type }}</td>
                <td>{{ $attribute->required ? 'Yes' : '' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">None</td></tr>
        @endforelse

        <tr><th colspan="4">Свои аттрибуты</th></tr>

        @forelse ($attributes as $attribute)
            <tr>
                <td>{{ $attribute->sort }}</td>
                <td>
                    <a href="{{ route('admin.adverts.categories.attributes.show', [$category, $attribute]) }}">{{ $attribute->name }}</a>
                </td>
                <td>{{ $attribute->type }}</td>
                <td>{{ $attribute->required ? 'Yes' : '' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">None</td></tr>
        @endforelse

        </tbody>
    </table>
@endsection
