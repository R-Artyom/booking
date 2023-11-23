<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class AdminIndexController extends Controller
{
    // Список бронирований
    public function __invoke(Request $request)
    {
        // Проверка прав пользователя
        $this->authorize('viewAnyAdmin', Booking::class);

        // Текущий пользователь
        $user = auth()->user();

        // * Админ
        if ($user->roles->containsStrict('name', 'admin')) {
            // Бронирования всех пользователей
            $bookings = Booking::paginate(5);
        }

        // * Менеджер отеля
        if ($user->roles->containsStrict('name', 'manager')) {
            // Список id отелей менеджера
            $hotelIds = $user->hotels->pluck('id')->toArray();
            // Список номеров
            $roomIds = Room::whereIn('hotel_id', $hotelIds)->get()->pluck('id')->toArray();
            // Бронирования отелей менеджера
            $bookings = Booking::whereIn('room_id', $roomIds)->orWhere('user_id', $user->id)->paginate(5);
        }

        // Шаблон бронирований
        return view('bookings.index', compact('bookings'));
    }
}
