@extends('layouts.app')

@section('content')
    @include('cabinet.adverts._nav')

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Дата обновления</th>
                <th>Название</th>
                <th>Регион</th>
                <th>Категория</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($adverts as $advert)
                <tr>
                    <td>{{ $advert->id }}</td>
                    <td>{{ $advert->updated_at }}</td>
                    <td><a href="{{ route('adverts.show', $advert) }}" target="_blank">{{ $advert->title }}</a></td>
                    <td>
                        @if ($advert->region)
                            {{ $advert->region->name }}
                        @endif
                    </td>
                    <td>{{ $advert->category->name }}</td>
                    <td>
                        @if ($advert->isDraft())
                            <span class="badge bg-secondary">Черновик</span>
                        @elseif ($advert->isOnModeration())
                            <span class="badge bg-primary">На Модерации</span>
                        @elseif ($advert->isActive())
                            <span class="badge bg-primary">Активно</span>
                        @elseif ($advert->isClosed())
                            <span class="badge bg-secondary">Закрыто</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $adverts->links() }}
@endsection
