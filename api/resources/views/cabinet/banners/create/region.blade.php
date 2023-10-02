@extends('layouts.app')

@section('content')
    @include('cabinet.banners._nav')

    @if ($region)
        <p>
            <a href="{{ route('cabinet.banners.create.banner', [$category, $region]) }}" class="btn btn-success">Добавить
                Объявление
                для {{ $region->name }}</a>
        </p>
    @else
        <p>
            <a href="{{ route('cabinet.banners.create.banner', [$category]) }}" class="btn btn-success">Добавить Объявление
                для всех регионов</a>
        </p>
    @endif

    <p>Или выбрать предложенный регион:</p>

    <ul>
        @foreach ($regions as $current)
            <li>
                <a href="{{ route('cabinet.banners.create.region', [$category, $current]) }}">{{ $current->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection
