<?php

namespace App\Listeners;

use App\Events\BookingDeleted;
use App\Notifications\BookingDeleted as NotificationsBookingDeleted;

// Слушатель "Бронирование удалено" (Отправка уведомления об удалении бронирования)
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
        // * Отправка уведомления "Бронирование удалено" (если разрешено)
        if (config('enable.userEmailNotifications') === true) {
            $event->booking->user->notify(new NotificationsBookingDeleted($event->booking));
        }
    }
}
