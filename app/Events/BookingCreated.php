<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Событие "Бронирование создано"
class BookingCreated
{
    use Dispatchable, SerializesModels;

    // Данные о бронировании
    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }
}
