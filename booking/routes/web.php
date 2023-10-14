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
});

// Бронирования
Route::group(['namespace' =>'Bookings'], function() {
    // Страница списка бронирований
    Route::get('/bookings', 'IndexController')->name('bookings.index');
    // Страница просмотра бронирования
    Route::get('/bookings/{booking}', 'ShowController')->name('bookings.show');
    // Сохранение данных бронирования номера
    Route::post('/bookings', 'StoreController')->name('bookings.store');
});

require __DIR__.'/auth.php';
