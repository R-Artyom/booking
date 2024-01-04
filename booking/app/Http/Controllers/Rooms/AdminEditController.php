<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;

class AdminEditController extends Controller
{
    // Форма редактирования отеля
    public function __invoke(Room $room)
    {
        // Шаблон редактирования отеля
        return view('rooms.admin-edit', compact('room'));
    }
}
