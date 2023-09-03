<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    // Просмотр бронирования
    public function __invoke(Booking $booking)
    {
        // Шаблон бронирования
        return view('bookings.show', compact('booking'));
    }

}
