<?php

declare(strict_types=1);

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

Breadcrumbs::register('cabinet', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Кабинет', route('cabinet'));
});

// Admin
Breadcrumbs::register('admin.home', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Admin', route('admin.home'));
});

// Users
Breadcrumbs::register('admin.users.index', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.home');
    $crumbs->push('Users', route('admin.users.index'));
});

Breadcrumbs::register('admin.users.create', function (Crumbs $crumbs): void {
    $crumbs->parent('admin.users.index');
    $crumbs->push('Create', route('admin.users.create'));
});

Breadcrumbs::register('admin.users.show', function (Crumbs $crumbs, User $user): void {
    $crumbs->parent('admin.users.index');
    $crumbs->push($user->name, route('admin.users.show', $user));
});

Breadcrumbs::register('admin.users.edit', function (Crumbs $crumbs, User $user): void {
    $crumbs->parent('admin.users.show', $user);
    $crumbs->push('Edit', route('admin.users.edit', $user));
});
