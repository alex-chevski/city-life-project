<?php

declare(strict_types=1);

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

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
    $crumbs->push('Change', route('password.reset'));
});

Breadcrumbs::register('cabinet', function (Crumbs $crumbs): void {
    $crumbs->parent('home');
    $crumbs->push('Кабинет', route('cabinet'));
});
