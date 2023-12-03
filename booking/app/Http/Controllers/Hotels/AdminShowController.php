<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class AdminShowController extends Controller
{
    // Просмотр отеля админ панели
    public function __invoke(Hotel $hotel)
    {
        // Номера отеля
        $rooms = $hotel->rooms()->with('bookings')->get();

        // Фильтр номеров по возможности бронирования
        $rooms = $rooms->map(function ($room, $key) {
            // Количество дней бронирования номера = 1
            $room['total_days'] = 1;
            // Цена за 1 ночь
            $room['total_price'] = $room->price;
            // Доступны все номера, т.к. не было запроса дат
            return $room;
        });

        // Шаблон отеля
        return view('hotels.admin-show', compact('hotel', 'rooms'));
    }
}
