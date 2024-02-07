<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class StatusesUpdateController extends Controller
{
    // Обновление статусов бронирований
    public function __invoke()
    {
        // Текущая дата
        $currentDate = now()->format('Y-m-d');
        // Поиск всех бронирований со статусом "Создан"
        $bookingsCollect = Booking::query()
            ->where('status_id', '=', config('status.Создан'))
            ->get();
        // Формирование нового статуса
        foreach ($bookingsCollect as $booking) {
            // Формирование статуса
            if (strtotime($currentDate) < strtotime($booking->started_at)) {
                continue;
            } elseif (strtotime($currentDate) > strtotime($booking->finished_at)) {
                $booking->status_id = config('status.Завершен');
            } else {
                $booking->status_id = config('status.Активен');
            }
            // Если были какие-либо изменения в модели (атрибуты, которые были изменены с момента последней синхронизации (attributes VS original))
            if ($booking->isDirty()) {
                // Обновление записи в таблице 'bookings'
                $booking->save();
            }
        }
    }
}
