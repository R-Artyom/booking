<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // Список бронирований
    public function __invoke(Request $request)
    {
        // Проверка прав пользователя
        $this->authorize('viewAny', Booking::class);

        // Текущий пользователь
        $user = auth()->user();

        // Бронирования текущего пользователя
        $bookings = Booking::where('user_id', $user->id)->paginate(5);

        // Шаблон бронирований
        return view('bookings.index', compact('bookings'));
    }

}
