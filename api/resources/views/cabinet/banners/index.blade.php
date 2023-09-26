@extends('layouts.app')

@section('content')
    @include('cabinet.banners._nav')

    <p><a href="{{ route('cabinet.banners.create') }}" class="btn btn-success">Добавить Баннер</a></p>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Регион</th>
                <th>Категория</th>
                <th>Опубликован</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td><a href="{{ route('cabinet.banners.show', $banner) }}" target="_blank">{{ $banner->name }}</a></td>
                    <td>
                        @if ($banner->region)
                            {{ $banner->region->name }}
                        @endif
                    </td>
                    <td>{{ $banner->category->name }}</td>
                    <td>{{ $banner->published_at }}</td>
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
            @endforeach

        </tbody>
    </table>

    {{ $banners->links() }}
@endsection
