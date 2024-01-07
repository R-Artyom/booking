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
    Route::get('/hotels', 'IndexController')->middleware('forgetSessionUrlPrePrevious')->name('hotels.index');
    // Страница просмотра отеля
    Route::get('/hotels/{hotel}', 'ShowController')->middleware('putSessionUrlPrePrevious')->name('hotels.show');

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
        // Удаление отеля
        Route::delete('/hotels/{hotel}', 'AdminDestroyController')->name('admin.hotels.destroy');
        // Страница управления отелями
        Route::get('/hotels', 'IndexController')->middleware('forgetSessionUrlPrePrevious')->name('admin.hotels.index');
        // Страница управления отелем
        Route::get('/hotels/{hotel}', 'AdminShowController')->middleware('putSessionUrlPrePrevious')->name('admin.hotels.show');
    });
});

// Номера отеля
Route::group(['namespace' =>'Rooms'], function() {
    // Админ роуты
    Route::group(['prefix' =>'admin'], function () {
        // Форма создания номера
        Route::get('/hotels/{hotel}/rooms/create', 'AdminCreateController')->name('admin.rooms.create');
        // Сохранение данных номера
        Route::post('/rooms/{hotel}', 'AdminStoreController')->name('admin.rooms.store');
        // Форма редактирования данных номера
        Route::get('/rooms/{room}/edit', 'AdminEditController')->name('admin.rooms.edit');
        // Редактирование данных номера
        Route::put('/rooms/{room}', 'AdminUpdateController')->name('admin.rooms.update');
        // Удаление номера
        Route::delete('/rooms/{room}', 'AdminDestroyController')->name('admin.rooms.destroy');
    });
});

// Удобства
Route::group(['namespace' =>'Facilities'], function() {
    // Админ роуты
    Route::group(['prefix' =>'admin'], function () {
        // Страница списка удобств
        Route::get('/facilities', 'AdminIndexController')->name('admin.facilities.index');
        // Форма создания удобства
        Route::get('/facilities/create', 'AdminCreateController')->name('admin.facilities.create');
        // Сохранение данных удобства
        Route::post('/facilities', 'AdminStoreController')->name('admin.facilities.store');
        // Форма редактирования данных удобства
        Route::get('/facilities/{facility}/edit', 'AdminEditController')->name('admin.facilities.edit');
        // Редактирование данных удобства
        Route::put('/facilities/{facility}', 'AdminUpdateController')->name('admin.facilities.update');
    });
});

// Пользователи
Route::group(['namespace' =>'Users'], function() {
    // Админ роуты
    Route::group(['prefix' =>'admin'], function () {
        // Страница списка пользователей
        Route::get('/users', 'AdminIndexController')->name('admin.users.index');
        // Редактирование данных пользователя
        Route::put('/users/{user}', 'AdminUpdateController')->name('admin.users.update');
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
