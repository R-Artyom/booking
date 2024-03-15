<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingReminder;

class RemindersRunController extends Controller
{
    public function __invoke()
    {
        // Отправка уведомлений с напоминаниями о заезде через 3 дня (если разрешено)
        if (config('enable.userEmailNotifications') === true) {
            // Дата: Текущая + 3 дня
            $startedAt = now()->addDays(3)->format('Y-m-d');
            // Поиск всех бронирований со стартом черз 3 дня
            $bookingsCollect = Booking::query()
                ->where('started_at', '=', $startedAt)
                ->get();
            // Рассылка уведомлений
            foreach ($bookingsCollect as $booking) {
                $booking->user->notify(new BookingReminder($booking));
            }
        }
    }
}
