<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @yield('meta')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body id="app">
    <header>
        <nav class="navbar navbar-expand-md">
            <div class="container">
                <a class="navbar-brand logo-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    <ul class="navbar-nav me-auto">

                        @include('layouts.partials.pages')

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">Вход</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">Регистрация</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @can('admin-panel')
                                        <a class="dropdown-item" href="{{ route('admin.home') }}">Админ панель</a>
                                    @endcan

                                    <a class="dropdown-item" href="{{ route('cabinet.home') }}">Кабинет</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Выйти') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @section('search')
            @include('layouts.partials.search', ['category' => null, 'action' => route('adverts.index')])
        @show
    </header>

    <main class="app-content py-3">
        <div class="container">
            @section('breadcrumbs', Breadcrumbs::render())
            @yield('breadcrumbs')
            @include('layouts.partials.flash')
            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="border-top pt-3">
                <p>&copy; {{ date('Y') }} CityLife - достоверная база данных о продаже и аренде жилой, загородной
                    и&nbsp;коммерческой недвижимости</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
