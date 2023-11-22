<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DestroyController extends Controller
{
    // Отмена бронирования
    public function __invoke(Booking $booking)
    {
        // Проверка прав пользователя
        $this->authorize('delete', $booking);

        // Удаление модели
        $booking->delete();

        // Страница просмотра бронирований
        $routeName = request()->prefix === '/admin' ? 'admin.bookings.index' : 'bookings.index';

        return redirect()->route($routeName);
    }
}
