<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

Auth::routes();

Route::get('/verify/{token}', 'App\Http\Controllers\Auth\RegisterController@verify')->name('register.verify');

Route::get('/cabinet', 'App\Http\Controllers\Cabinet\HomeController@index')->name('cabinet');

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'App\Http\Controllers\Admin',
        'middleware' => ['auth'],
    ],
    static function (): void {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('users', 'UsersController');
        Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');
    }
);
