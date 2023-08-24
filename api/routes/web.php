<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

Auth::routes();

Route::get('/login/phone', 'App\Http\Controllers\Auth\LoginController@phone')->name('login.phone');
Route::post('/login/phone', 'App\Http\Controllers\Auth\LoginController@verify');

Route::get('/verify/{token}', 'App\Http\Controllers\Auth\RegisterController@verify')->name('register.verify');

Route::group([
    'prefix' => 'adverts',
    'as' => 'adverts.',
    'namespace' => 'App\Http\Controllers\Adverts',
], static function (): void {
    Route::get('/show/{advert}', 'AdvertController@show')->name('show');
    Route::post('/show/{advert}/phone', 'AdvertController@phone')->name('phone');
    Route::post('/show/{advert}/favorites', 'FavoriteController@add')->name('favorites');
    Route::delete('/show/{advert}/favorites', 'FavoriteController@remove');

    Route::get('/{adverts_path?}', 'AdvertController@index')->name('index')->where('adverts_path', '.+');
});

Route::group(
    [
        'prefix' => 'cabinet',
        'as' => 'cabinet.',
        'namespace' => 'App\Http\Controllers\Cabinet',
        'middleware' => ['auth'],
    ],
    static function (): void {
        Route::get('/', 'HomeController@index')->name('home');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], static function (): void {
            Route::get('/', 'ProfileController@index')->name('home');
            Route::get('/edit', 'ProfileController@edit')->name('edit');
            Route::put('/update', 'ProfileController@update')->name('update');
            Route::post('/phone', 'PhoneController@request');
            Route::get('/phone', 'PhoneController@form')->name('phone');
            Route::put('/phone', 'PhoneController@verify')->name('phone.verify');

            Route::post('/phone/auth', 'PhoneController@auth')->name('phone.auth');
        });

        Route::get('favorites', 'FavoriteController@index')->name('favorites.index');
        Route::delete('favorites/{advert}', 'FavoriteController@remove')->name('favorites.remove');

        Route::group([
            'prefix' => 'adverts',
            'as' => 'adverts.',
            'namespace' => 'Adverts',
            'middleware' => [App\Http\Middleware\FilledProfile::class],
        ], static function (): void {
            Route::get('/', 'AdvertController@index')->name('index');
            Route::get('/create', 'CreateController@category')->name('create');
            Route::get('/create/region/{category}/{region?}', 'CreateController@region')->name('create.region');
            Route::get('/create/advert/{category}/{region?}', 'CreateController@advert')->name('create.advert');
            Route::post('/create/advert/{category}/{region?}', 'CreateController@store')->name('create.advert.store');

            Route::get('/{advert}/edit', 'ManageController@editForm')->name('edit');
            Route::put('/{advert}/edit', 'ManageController@edit');
            Route::get('/{advert}/photos', 'ManageController@photosForm')->name('photos');
            Route::post('/{advert}/photos', 'ManageController@photos');
            Route::get('/{advert}/attributes', 'ManageController@attributesForm')->name('attributes');
            Route::post('/{advert}/attributes', 'ManageController@attributes');
            Route::post('/{advert}/send', 'ManageController@send')->name('send');
            Route::post('/{advert}/close', 'ManageController@close')->name('close');
            Route::delete('/{advert}/destroy', 'ManageController@destroy')->name('destroy');
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

            Route::group(['prefix' => 'adverts', 'as' => 'adverts.'], static function (): void {
                Route::get('/', 'AdvertController@index')->name('index');
                Route::get('/{advert}/edit', 'AdvertController@editForm')->name('edit');
                Route::put('/{advert}/edit', 'AdvertController@editForm');
                Route::get('/{advert}/photos', 'AdvertController@photosForm')->name('photos');
                Route::post('/{advert}/photos', 'AdvertController@photos');
                Route::get('{advert}/attributes', 'AdvertController@attributesForm')->name('attributes');
                Route::post('{advert}/attributes', 'AdvertController@attributes');
                Route::post('{advert}/moderate', 'AdvertController@moderate')->name('moderate');
                Route::get('{advert}/reject', 'AdvertController@rejectForm')->name('reject');
                Route::post('{advert}/reject', 'AdvertController@reject');
                Route::delete('{advert}/destroy', 'AdvertController@destroy')->name('destroy');
            });
        });
    }
);
