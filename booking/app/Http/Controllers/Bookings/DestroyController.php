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

        // Предыдущая страница для перенаправления после удаления (или данные из сессии или предыдущая страница (если в сессии нет))
        $previousUrl = request()->session()->get('pre_previous_url', url()->previous());

        // Страница просмотра бронирований c учетом пагинации, фильтров и сортировки
        return redirect()->to($previousUrl);
    }
}
