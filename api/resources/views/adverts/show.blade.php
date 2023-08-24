@extends('layouts.app')

@section('content')

    @if ($advert->isDraft())
        <div class="alert alert-danger">
            Это черновик
        </div>
        @if ($advert->reject_reason)
            <div class="alert alert-danger">
                {{ $advert->reject_reason }}
            </div>
        @endif
    @endif

    @can ('manage-adverts')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.adverts.edit', $advert) }}" class="btn btn-primary me-1">Редактировать</a>
            <a href="{{ route('admin.adverts.adverts.photos', $advert) }}" class="btn btn-primary me-1">Фотографии</a>

            @if ($advert->isOnModeration())
                <form method="POST" action="{{ route('admin.adverts.adverts.moderate', $advert) }}" class="me-1">
                    @csrf
                    <button class="btn btn-success">Опубликовать</button>
                </form>
            @endif

            @if ($advert->isOnModeration() || $advert->isActive())
                <a href="{{ route('admin.adverts.adverts.reject', $advert) }}" class="btn btn-danger me-1">Отклонить</a>
            @endif

            <form method="POST" action="{{ route('admin.adverts.adverts.destroy', $advert) }}" class="me-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Удалить</button>
            </form>
        </div>
    @endcan

    @can ('edit-own-advert', $advert)
            <div class="d-flex flex-row mb-3">
                <a href="{{ route('cabinet.adverts.edit', $advert) }}" class="btn btn-primary me-1">Редактировать</a>
                <a href="{{ route('cabinet.adverts.photos', $advert) }}" class="btn btn-primary me-1">Фотографии</a>

                @if ($advert->isDraft())
                    <form method="POST" action="{{ route('cabinet.adverts.send', $advert) }}" class="me-1">
                        @csrf
                        <button class="btn btn-success">Опубликовать</button>
                    </form>
                @endif
                @if ($advert->isActive())
                    <form method="POST" action="{{ route('cabinet.adverts.close', $advert) }}" class="me-1">
                        @csrf
                        <button class="btn btn-success">Закрыть</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" class="me-1">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Удалить</button>
                </form>
            </div>
    @endcan

    <div class="row">
        <div class="col-md-9">

            <p class="float-right" style="font-size: 36px;">{{ $advert->price }}</p>
            <h1 style="margin-bottom: 10px">{{ $advert->title  }}</h1>
            <p>
                @if ($advert->expires_at)
                    Дата обновления: {{ $advert->published_at }} &nbsp;
                @endif
                @if ($advert->expires_at)
                    Дата истечения: {{ $advert->expires_at }}
                @endif
            </p>

            <div style="margin-bottom: 20px">
                <div class="row">
                    <div class="col-10">
                        <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd"></div>
                    </div>
                    <div class="col-2">
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                    </div>
                </div>
            </div>

            <p>{!! nl2br(e($advert->content)) !!}</p>

            <hr/>

            <table class="table table-bordered">
                <tbody>
                @foreach ($advert->category->allAttributes() as $attribute)
                    <tr>
                        <th>{{ $attribute->name }}</th>
                        <td>{{ $advert->getValue($attribute->id) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>


            <p>Address: {{ $advert->address }}</p>

            <div style="margin: 20px 0; border: 1px solid #ddd">

                <div id="map" style="width: 100%; height: 250px"></div>

                Здесь будет карта(yandex)


            </div>

            <p style="margin-bottom: 20px">Автор: {{ $advert->user->name }}</p>

            <div class="d-flex flex-row mb-3">
                <span class="btn btn-success me-1"><span class="fa fa-envelope"></span>Отправить сообщение</span>
                <span class="btn btn-primary phone-button me-1" data-source="{{ route('adverts.phone', $advert) }}"><span class="fa fa-phone"></span> <span class="number">Показать номер телефона</span></span>
                @if ($user && $user->hasInFavorites($advert->id))
                    <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="me-1">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-secondary"><span class="fa fa-star"></span>Удалить из избранного</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="me-1">
                        @csrf
                        <button class="btn btn-warning"><span class="fa fa-star"></span>Добавить в избранное</button>
                    </form>
                @endif
            </div>

            <hr/>

            <div class="h3">Похожие Объявления</div>

            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card">
                        <img class="card-img-top" src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb" alt=""/>
                        <div class="card-body">
                            <div class="card-title h4 mt-0" style="margin: 10px 0"><a href="#">The First Thing</a></div>
                            <p class="card-text" style="color: #666">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card">
                        <img class="card-img-top" src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb" alt=""/>
                        <div class="card-body">
                            <div class="card-title h4 mt-0" style="margin: 10px 0"><a href="#">The First Thing</a></div>
                            <p class="card-text" style="color: #666">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card">
                        <img class="card-img-top" src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb" alt=""/>
                        <div class="card-body">
                            <div class="card-title h4 mt-0" style="margin: 10px 0"><a href="#">The First Thing</a></div>
                            <p class="card-text" style="color: #666">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3">
            <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px"></div>
            <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px"></div>
        </div>
    </div>
@endsection
