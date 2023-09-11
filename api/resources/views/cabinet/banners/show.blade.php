@extends('layouts.app')

@section('content')
    @include('cabinet.banners._nav')

    <div class="d-flex flex-row mb-3">

        @if ($banner->canBeChanged())
            <a href="{{ route('cabinet.banners.edit', $banner) }}" class="btn btn-primary me-1">Редактировать</a>
            <a href="{{ route('cabinet.banners.file', $banner) }}" class="btn btn-primary me-1">Добавить новый баннер</a>
        @endif

        @if ($banner->isDraft())
            <form method="POST" action="{{ route('cabinet.banners.send', $banner) }}" class="me-1">
                @csrf
                <button class="btn btn-success">Отправить на модерацию</button>
            </form>
        @endif

        @if ($banner->isOnModeration())
            <form method="POST" action="{{ route('cabinet.banners.cancel', $banner) }}" class="me-1">
                @csrf
                <button class="btn btn-secondary">Отозвать с модерации</button>
            </form>
        @endif

        @if ($banner->isModerated())
            <form method="POST" action="{{ route('cabinet.banners.order', $banner) }}" class="me-1">
                @csrf
                <button class="btn btn-success">Оплатить</button>
            </form>
        @endif

        @if ($banner->canBeRemoved())
            <form method="POST" action="{{ route('cabinet.banners.destroy', $banner) }}" class="me-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Удалить</button>
            </form>
        @endif
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $banner->id }}</td>
            </tr>
            <tr>
                <th>Имя</th>
                <td>{{ $banner->name }}</td>
            </tr>
            <tr>
                <th>Регион</th>
                <td>
                    @if ($banner->region)
                        {{ $banner->region->name }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Категория</th>
                <td>{{ $banner->category->name }}</td>
            </tr>
            <tr>
                <th>Статус</th>
                <td>
                    @if ($banner->isDraft())
                        <span class="badge bg-secondary">Draft</span>
                    @elseif ($banner->isOnModeration())
                        <span class="badge bg-primary">Moderation</span>
                    @elseif ($banner->isModerated())
                        <span class="badge bg-success">Ready to Payment</span>
                    @elseif ($banner->isOrdered())
                        <span class="badge bg-warning">Waiting for Payment</span>
                    @elseif ($banner->isActive())
                        <span class="badge bg-primary">Active</span>
                    @elseif ($banner->isClosed())
                        <span class="badge bg-secondary">Closed</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Url</th>
                <td><a href="{{ $banner->url }}">{{ $banner->url }}</a></td>
            </tr>
            <tr>
                <th>Установленный лимит просмотров</th>
                <td>{{ $banner->limit }}</td>
            </tr>
            <tr>
                <th>Просмотры</th>
                <td>{{ $banner->views }}</td>
            </tr>
            <tr>
                <th>Дата Публикации</th>
                <td>{{ $banner->published_at }}</td>
            </tr>
        </tbody>
    </table>

    <div class="card">
        <div class="card-body">
            <img src="{{ asset('storage/' . $banner->file) }}" />
        </div>
    </div>
@endsection
