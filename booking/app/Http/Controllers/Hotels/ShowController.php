<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    // Просмотр отеля
    public function __invoke(Hotel $hotel)
    {
        // Номера отеля
        $rooms = $hotel->rooms; // То же самое, что $rooms = $hotel->rooms()->get();
        // Шаблон отеля
        return view('hotels.show', compact('hotel', 'rooms'));
    }

}
