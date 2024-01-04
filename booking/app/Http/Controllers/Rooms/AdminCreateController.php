<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class AdminCreateController extends Controller
{
    // Форма создания номера отеля
    public function __invoke(Hotel $hotel)
    {
        // Шаблон формы создания отеля
        return view('rooms.admin-create', compact('hotel'));
    }
}
