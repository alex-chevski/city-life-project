@foreach (array_slice($menuPages, 0, 3) as $page)
    <li><a class="nav-link" href="{{ route('page', page_path($page)) }}">{{ $page->getMenuTitle() }}</a></li>
@endforeach

@if ($morePages = array_slice($menuPages, 3))
    <li class="nav-item dropdown">
        <a id="dropdownMenuLink" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            More... <span class="caret"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @foreach ($morePages as $page)
                <a class="dropdown-item" href="{{ route('page', page_path($page)) }}">{{ $page->getMenuTitle() }}</a>
            @endforeach
        </div>
    </li>
@endif
