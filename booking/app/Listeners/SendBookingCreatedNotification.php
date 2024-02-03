<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Notifications\BookingCreated as NotificationsBookingCreated;

// Слушатль "Бронирование создано" (Отправка уведомления о создании бронирования)
class SendBookingCreatedNotification
{
    /**
     * Handle the event.
     *
     * @param BookingCreated $event
     * @return void
     */
    public function handle(BookingCreated $event)
    {
        // * Отправка уведомления "Бронирование создано"
        $event->booking->user->notify(new NotificationsBookingCreated($event->booking));
    }
}
