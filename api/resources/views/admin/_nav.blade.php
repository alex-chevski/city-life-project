<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link{{ $page === '' ? ' active' : '' }}" href="{{ route('admin.home') }}">Доска
            Объявлений</a></li>
    @can('manage-adverts')
        <li class="nav-item"><a class="nav-link{{ $page === 'adverts' ? ' active' : '' }}"
                href="{{ route('admin.adverts.adverts.index') }}">Объявления</a></li>
    @endcan


    @can('manage-banners')
        <li class="nav-item"><a class="nav-link{{ $page === 'banners' ? ' active' : '' }}"
                href="{{ route('admin.banners.index') }}">Баннеры</a></li>
    @endcan

    @can('manage-regions')
        <li class="nav-item"><a class="nav-link{{ $page === 'regions' ? ' active' : '' }}"
                href="{{ route('admin.regions.index') }}">Регионы</a></li>
    @endcan
    @can('manage-adverts-categories')
        <li class="nav-item"><a class="nav-link{{ $page === 'adverts_categories' ? ' active' : '' }}"
                href="{{ route('admin.adverts.categories.index') }}">Категории</a></li>
    @endcan

    @can('manage-users')
        <li class="nav-item"><a class="nav-link{{ $page === 'users' ? ' active' : '' }}"
                href="{{ route('admin.users.index') }}">Пользователи</a></li>
    @endcan

</ul>
