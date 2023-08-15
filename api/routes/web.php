<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

Auth::routes();

Route::get('/verify/{token}', 'App\Http\Controllers\Auth\RegisterController@verify')->name('register.verify');

Route::group(
    [
        'prefix' => 'cabinet',
        'as' => 'cabinet.',
        'namespace' => 'App\Http\Controllers\Cabinet',
        'middleware' => ['auth'],
    ],
    static function (): void {
        Route::get('/', 'HomeController@index')->name('home');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function (){
            Route::get('/', 'ProfileController@index')->name('home');
            Route::get('/edit', 'ProfileController@edit')->name('edit');
            Route::put('/update', 'ProfileController@update')->name('update');
            Route::post('/phone', 'PhoneController@request');
            Route::get('/phone', 'PhoneController@form')->name('phone');
            Route::put('/phone', 'PhoneController@verify')->name('phone.verify');
        });

    }
);

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'App\Http\Controllers\Admin',
        'middleware' => ['auth', 'can:admin-panel'],
    ],
    static function (): void {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('users', 'UsersController');
        Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');
        Route::resource('regions', 'RegionController');

        Route::group(['prefix' => 'adverts', 'as' => 'adverts.', 'namespace' => 'Adverts'], static function (): void {
            Route::resource('categories', 'CategoryController');

            Route::group(['prefix' => 'categories/{category}', 'as' => 'categories.'], static function (): void {
                Route::post('/first', 'CategoryController@first')->name('first');
                Route::post('/up', 'CategoryController@up')->name('up');
                Route::post('/down', 'CategoryController@down')->name('down');
                Route::post('/last', 'CategoryController@last')->name('last');
                Route::resource('attributes', 'AttributeController')->except('index');
            });
        });
    }
);
