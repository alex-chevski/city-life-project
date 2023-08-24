@extends('layouts.app')

@section('content')
    @include('cabinet.adverts._nav')

    <p>Выберите категорию:</p>


    @include('cabinet.adverts.create._categories', ['categories' => $categories])

@endsection
