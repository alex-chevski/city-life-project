<?php

declare(strict_types=1);

use App\Http\Router\AdvertsPath;
use App\Http\Router\PagePath;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use App\Models\Banner\Banner;
use App\Models\Page;
use App\Models\Region;
use App\Models\Ticket\Ticket;
use App\Models\User\User;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Str;

Breadcrumbs::register('home', static function (Crumbs $crumbs): void {
    $crumbs->push('Главная страница', route('home'));
});

Breadcrumbs::register('login', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Логин', route('login'));
});

Breadcrumbs::register('login.phone', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Логин', route('login.phone'));
});

Breadcrumbs::register('register', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Регистрация', route('register'));
});

Breadcrumbs::register('password.request', function (Crumbs $crumbs): void {
    $crumbs->parent('login');
    $crumbs->push('Сбросить пароль', route('password.request'));
});

Breadcrumbs::register('password.reset', function (Crumbs $crumbs): void {
    $crumbs->parent('password.request');
    $crumbs->push('', route('password.reset', Str::uuid()));
});

Breadcrumbs::register('page', function (Crumbs $crumbs, PagePath $path): void {
    if ($parent = $path->page->parent) {
        $crumbs->parent('page', $path->withPage($path->page->parent));
    } else {
        $crumbs->parent('home');
    }
    $crumbs->push($path->page->title, route('page', $path));
});

// Adverts

Breadcrumbs::register('adverts.inner_region', function (Crumbs $crumbs, AdvertsPath $path): void {
    if ($path->region && $parent = $path->region->parent) {
        $crumbs->parent('adverts.inner_region', $path->withRegion($parent));
    } else {
        $crumbs->parent('home');
        $crumbs->push('Объявления', route('adverts.index'));
    }
    if ($path->region) {
        $crumbs->push($path->region->name, route('adverts.index', $path));
    }
});

Breadcrumbs::register('adverts.inner_category', function (Crumbs $crumbs, AdvertsPath $path, AdvertsPath $orig): void {
    if ($path->category && $parent = $path->category->parent) {
        $crumbs->parent('adverts.inner_category', $path->withCategory($parent), $orig);
    } else {
        $crumbs->parent('adverts.inner_region', $orig);
    }
    if ($path->category) {
        $crumbs->push($path->category->name, route('adverts.index', $path));
    }
});

Breadcrumbs::register('adverts.index', function (Crumbs $crumbs, AdvertsPath $path = null): void {
    $path = $path ?: adverts_path(null, null);
    $crumbs->parent('adverts.inner_category', $path, $path);
});

Breadcrumbs::register('adverts.show', function (Crumbs $crumbs, Advert $advert): void {
    $crumbs->parent('adverts.index', adverts_path($advert->region, $advert->category));
    $crumbs->push($advert->title, route('adverts.show', $advert));
});

// Cabinet
Breadcrumbs::register('cabinet.home', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Кабинет', route('cabinet.home'));
});

Breadcrumbs::register('cabinet.profile.home', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Профиль', route('cabinet.profile.home'));
});

Breadcrumbs::register('cabinet.profile.edit', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Редактировать профиль', route('cabinet.profile.edit'));
});

Breadcrumbs::register('cabinet.profile.phone', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Телефон', route('cabinet.profile.phone'));
});

// Cabinet Adverts

Breadcrumbs::register('cabinet.adverts.index', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Мои объявления', route('cabinet.adverts.index'));
});

Breadcrumbs::register('cabinet.adverts.create', function (Crumbs $crumbs): void {
    $crumbs->parent('adverts.index');
    $crumbs->push('Создать', route('cabinet.adverts.create'));
});

Breadcrumbs::register('cabinet.adverts.edit', function (Crumbs $crumbs, Advert $advert): void {
    $crumbs->parent('adverts.index');
    $crumbs->push('Редактировать', route('cabinet.adverts.edit', [$advert]));
});

Breadcrumbs::register('cabinet.adverts.photos', function (Crumbs $crumbs, Advert $advert): void {
    $crumbs->parent('adverts.index');
    $crumbs->push('Фотографии', route('cabinet.adverts.edit', [$advert]));
});

Breadcrumbs::register('cabinet.adverts.create.region', function (Crumbs $crumbs, Category $category, Region $region = null): void {
    $crumbs->parent('cabinet.adverts.create');
    $crumbs->push($category->name, route('cabinet.adverts.create.region', [$category, $region]));
});

Breadcrumbs::register('cabinet.adverts.create.advert', function (Crumbs $crumbs, Category $category, Region $region = null): void {
    $crumbs->parent('cabinet.adverts.create.region', $category, $region);
    $crumbs->push($region ? $region->name : 'All', route('cabinet.adverts.create.advert', [$category, $region]));
});

// favorites
Breadcrumbs::register('cabinet.favorites.index', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Объявления', route('cabinet.favorites.index'));
});

// Cabinet Banners

Breadcrumbs::register('cabinet.banners.index', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Баннеры', route('cabinet.banners.index'));
});

Breadcrumbs::register('cabinet.banners.show', function (Crumbs $crumbs, Banner $banner): void {
    $crumbs->parent('cabinet.banners.index');
    $crumbs->push($banner->name, route('cabinet.banners.show', $banner));
});

Breadcrumbs::register('cabinet.banners.edit', function (Crumbs $crumbs, Banner $banner): void {
    $crumbs->parent('cabinet.banners.show', $banner);
    $crumbs->push('Редактировать', route('cabinet.banners.edit', $banner));
});

Breadcrumbs::register('cabinet.banners.file', function (Crumbs $crumbs, Banner $banner): void {
    $crumbs->parent('cabinet.banners.show', $banner);
    $crumbs->push('Файл', route('cabinet.banners.file', $banner));
});

Breadcrumbs::register('cabinet.banners.create', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.banners.index');
    $crumbs->push('Создать', route('cabinet.banners.create'));
});

Breadcrumbs::register('cabinet.banners.create.region', function (Crumbs $crumbs, Category $category, Region $region = null): void {
    $crumbs->parent('cabinet.banners.create');
    $crumbs->push($category->name, route('cabinet.banners.create.region', [$category, $region]));
});

Breadcrumbs::register('cabinet.banners.create.banner', function (Crumbs $crumbs, Category $category, Region $region = null): void {
    $crumbs->parent('cabinet.banners.create.region', $category, $region);
    $crumbs->push($region ? $region->name : 'All', route('cabinet.banners.create.banner', [$category, $region]));
});

// Pages

Breadcrumbs::register('admin.pages.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Страницы', route('admin.pages.index'));
});

Breadcrumbs::register('admin.pages.create', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.pages.index');
    $crumbs->push('Создать', route('admin.pages.create'));
});

Breadcrumbs::register('admin.pages.show', function (Crumbs $crumbs, Page $page): void {
    if ($parent = $page->parent) {
        $crumbs->parent('admin.pages.show', $parent);
    } else {
        $crumbs->parent('admin.pages.index');
    }
    $crumbs->push($page->title, route('admin.pages.show', $page));
});

Breadcrumbs::register('admin.pages.edit', function (Crumbs $crumbs, Page $page): void {
    $crumbs->parent('admin.pages.show', $page);
    $crumbs->push('Редактировать', route('admin.pages.edit', $page));
});

// Cabinet Tickets

Breadcrumbs::register('cabinet.tickets.index', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Tickets', route('cabinet.tickets.index'));
});

Breadcrumbs::register('cabinet.tickets.create', function (Crumbs $crumbs): void {
    $crumbs->parent('cabinet.tickets.index');
    $crumbs->push('Create', route('cabinet.tickets.create'));
});

Breadcrumbs::register('cabinet.tickets.show', function (Crumbs $crumbs, Ticket $ticket): void {
    $crumbs->parent('cabinet.tickets.index');
    $crumbs->push($ticket->subject, route('cabinet.tickets.show', $ticket));
});

// Admin

Breadcrumbs::register('admin.home', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Панель админа', route('admin.home'));
});

// Tickets

Breadcrumbs::register('admin.tickets.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Tickets', route('admin.tickets.index'));
});

Breadcrumbs::register('admin.tickets.show', function (Crumbs $crumbs, Ticket $ticket): void {
    $crumbs->parent('admin.tickets.index');
    $crumbs->push($ticket->subject, route('admin.tickets.show', $ticket));
});

Breadcrumbs::register('admin.tickets.edit', function (Crumbs $crumbs, Ticket $ticket): void {
    $crumbs->parent('admin.tickets.show', $ticket);
    $crumbs->push('Edit', route('admin.tickets.edit', $ticket));
});

// Users
Breadcrumbs::register('admin.users.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Пользователи', route('admin.users.index'));
});

Breadcrumbs::register('admin.users.create', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.users.index');
    $crumbs->push('Создать', route('admin.users.create'));
});

Breadcrumbs::register('admin.users.show', function (Crumbs $crumbs, User $user): void {
    $crumbs->parent('admin.users.index');
    $crumbs->push($user->name, route('admin.users.show', $user));
});

Breadcrumbs::register('admin.users.edit', function (Crumbs $crumbs, User $user): void {
    $crumbs->parent('admin.users.show', $user);
    $crumbs->push('Изменить', route('admin.users.edit', $user));
});

// Banners

Breadcrumbs::register('admin.banners.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Баннеры', route('admin.banners.index'));
});

Breadcrumbs::register('admin.banners.show', function (Crumbs $crumbs, Banner $banner): void {
    $crumbs->parent('admin.banners.index');
    $crumbs->push($banner->name, route('admin.banners.show', $banner));
});

Breadcrumbs::register('admin.banners.edit', function (Crumbs $crumbs, Banner $banner): void {
    $crumbs->parent('admin.banners.show', $banner);
    $crumbs->push('Редактировать', route('admin.banners.edit', $banner));
});

Breadcrumbs::register('admin.banners.reject', function (Crumbs $crumbs, Banner $banner): void {
    $crumbs->parent('admin.banners.show', $banner);
    $crumbs->push('Отклонить', route('admin.banners.reject', $banner));
});

// Regions
Breadcrumbs::register('admin.regions.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Регионы', route('admin.regions.index'));
});

Breadcrumbs::register('admin.regions.create', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.regions.index');
    $crumbs->push('Создать', route('admin.regions.create'));
});

Breadcrumbs::register('admin.regions.show', function (Crumbs $crumbs, Region $region): void {
    if ($parent = $region->parent) {
        $crumbs->parent('admin.regions.show', $parent);
    } else {
        $crumbs->push($region->name, route('admin.regions.index', $region));
    }

    $crumbs->push($region->name, route('admin.regions.show', $region));
});

Breadcrumbs::register('admin.regions.edit', function (Crumbs $crumbs, Region $region): void {
    $crumbs->parent('admin.regions.show', $region);
    $crumbs->push('Изменить', route('admin.regions.edit', $region));
});

// categories
Breadcrumbs::register('admin.adverts.categories.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Категории', route('admin.adverts.categories.index'));
});

Breadcrumbs::register('admin.adverts.categories.create', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.adverts.categories.index');
    $crumbs->push('Создать', route('admin.adverts.categories.create'));
});

Breadcrumbs::register('admin.adverts.categories.show', function (Crumbs $crumbs, Category $category): void {
    if ($parent = $category->parent) {
        $crumbs->parent('admin.adverts.categories.show', $parent);
    } else {
        $crumbs->parent('admin.adverts.categories.index');
    }
    $crumbs->push($category->name, route('admin.adverts.categories.show', $category));
});

Breadcrumbs::register('admin.adverts.categories.edit', function (Crumbs $crumbs, Category $category): void {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Изменить', route('admin.adverts.categories.edit', $category));
});

// Advert Category Attributes

Breadcrumbs::register('admin.adverts.categories.attributes.create', function (Crumbs $crumbs, Category $category): void {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Создать', route('admin.adverts.categories.attributes.create', $category));
});

Breadcrumbs::register('admin.adverts.categories.attributes.show', function (Crumbs $crumbs, Category $category, Attribute $attribute): void {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push($attribute->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
});

Breadcrumbs::register('admin.adverts.categories.attributes.edit', function (Crumbs $crumbs, Category $category, Attribute $attribute): void {
    $crumbs->parent('admin.adverts.categories.attributes.show', $category, $attribute);
    $crumbs->push('Редактирование', route('admin.adverts.categories.attributes.edit', [$category, $attribute]));
});

// Adverts Admin
Breadcrumbs::register('admin.adverts.adverts.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Категории', route('admin.adverts.adverts.index'));
});

Breadcrumbs::register('admin.adverts.adverts.', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Категории', route('admin.adverts.adverts.index'));
});

Breadcrumbs::register('admin.adverts.adverts.photos', function (Crumbs $crumbs, Advert $advert): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Фотографии', route('admin.adverts.adverts.index', [$advert]));
});

Breadcrumbs::register('admin.adverts.adverts.edit', function (Crumbs $crumbs, Advert $advert): void {
    $crumbs->parent('admin.home');
    $crumbs->push($advert->title, route('admin.adverts.adverts.edit', $advert));
});

Breadcrumbs::register('admin.adverts.adverts.reject', function (Crumbs $crumbs, Advert $advert): void {
    $crumbs->parent('admin.home');
    $crumbs->push($advert->title, route('admin.adverts.adverts.reject', $advert));
});
