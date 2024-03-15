<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class AdminShowController extends Controller
{
    // Просмотр бронирования
    public function __invoke(Booking $booking)
    {
        // Проверка прав пользователя
        $this->authorize('view', $booking);
        // Шаблон бронирования
        return view('bookings.show', compact('booking'));
    }
}
