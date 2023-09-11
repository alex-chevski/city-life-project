@extends('layouts.app')

@section('content')
    @include('admin.banners._nav')

    <div class="d-flex flex-row mb-3">

        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary me-1">Редактировать</a>

        @if ($banner->isOnModeration())
            <form method="POST" action="{{ route('admin.banners.moderate', $banner) }}" class="me-1">
                @csrf
                <button class="btn btn-success">Сделать Активным</button>
            </form>
        @endif

        @if ($banner->isOrdered())
            <form method="POST" action="{{ route('admin.banners.pay', $banner) }}" class="me-1">
                @csrf
                <button class="btn btn-success">Отметить как оплаченный</button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="me-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
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
