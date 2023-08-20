<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // Список бронирований
    public function __invoke()
    {
        // Бронирования
        $bookings = Booking::with('room')->get();
        // Шаблон бронирований
        return view('bookings.index', compact('bookings'));
    }

}
