@extends('layouts.app')

@section('content')
    @include('admin.pages._nav')

    <p><a href="{{ route('admin.pages.create') }}" class="btn btn-success">Добавить страницу</a></p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Заголовок</th>
                <th>Отображение адреса</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            @foreach ($pages as $page)
                <tr>
                    <td>
                        @for ($i = 0; $i < $page->depth; $i++)
                            &mdash;
                        @endfor
                        <a href="{{ route('admin.pages.show', $page) }}">{{ $page->title }}</a>
                    </td>
                    <td>{{ $page->menu_title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>
                        <div class="d-flex flex-row">
                            <form method="POST" action="{{ route('admin.pages.first', $page) }}" class="me-1">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary">
                                    First</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pages.up', $page) }}" class="me-1">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary">Up</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pages.down', $page) }}" class="me-1">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary">Down</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pages.last', $page) }}" class="me-1">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary">Last</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
