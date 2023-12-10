<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Главная страница
Route::get('/', 'MainController@index')->name('index');

// Отели
Route::group(['namespace' =>'Hotels'], function() {
    // Страница списка отелей
    Route::get('/hotels', 'IndexController')->name('hotels.index');
    // Страница просмотра отеля
    Route::get('/hotels/{hotel}', 'ShowController')->name('hotels.show');

    // Админ роуты
    Route::group(['prefix' =>'admin'], function () {
        // Форма создания отеля
        Route::get('/hotels/create', 'AdminCreateController')->name('admin.hotels.create');
        // Сохранение данных отеля
        Route::post('/hotels', 'AdminStoreController')->name('admin.hotels.store');
        // Форма редактирования данных отеля
        Route::get('/hotels/{hotel}/edit', 'AdminEditController')->name('admin.hotels.edit');
        // Редактирование данных отеля
        Route::put('/hotels/{hotel}', 'AdminUpdateController')->name('admin.hotels.update');
        // Страница управления отелями
        Route::get('/hotels', 'AdminIndexController')->name('admin.hotels.index');
        // Страница управления отелями
        Route::get('/hotels/{hotel}', 'AdminShowController')->name('admin.hotels.show');
    });
});

// Бронирования
Route::group(['namespace' =>'Bookings'], function() {
    // Страница списка бронирований
    Route::get('/bookings', 'IndexController')->middleware('forgetSessionUrlPrePrevious')->name('bookings.index');
    // Страница просмотра бронирования
    Route::get('/bookings/{booking}', 'ShowController')->middleware('putSessionUrlPrePrevious')->name('bookings.show');
    // Сохранение данных бронирования номера
    Route::post('/bookings', 'StoreController')->name('bookings.store');
    // Отмена бронирования
    Route::delete('/bookings/{booking}', 'DestroyController')->name('bookings.destroy');

    // Админ роуты
    Route::group(['prefix' =>'admin'], function () {
        // Страница управления бронированиями (соответствует '/admin/bookings')
        Route::get('/bookings', 'AdminIndexController')->middleware('forgetSessionUrlPrePrevious')->name('admin.bookings.index');
        // Страница управления бронированием
        Route::get('/bookings/{booking}', 'AdminShowController')->middleware('putSessionUrlPrePrevious')->name('admin.bookings.show');
    });
});

require __DIR__.'/auth.php';
