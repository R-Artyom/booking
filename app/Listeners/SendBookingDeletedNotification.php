<?php

namespace App\Listeners;

use App\Events\BookingDeleted;
use App\Notifications\BookingDeleted as NotificationsBookingDeleted;

// Слушатль "Бронирование удалено" (Отправка уведомления об удалении бронирования)
class SendBookingDeletedNotification
{

    /**
     * Handle the event.
     *
     * @param BookingDeleted $event
     * @return void
     */
    public function handle(BookingDeleted $event)
    {
        // * Отправка уведомления "Бронирование удалено"
        $event->booking->user->notify(new NotificationsBookingDeleted($event->booking));
    }
}
