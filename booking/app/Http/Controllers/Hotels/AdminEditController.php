<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class AdminEditController extends Controller
{
    // Форма редактирования отеля
    public function __invoke(Hotel $hotel)
    {
        // Шаблон редактирования отеля
        return view('hotels.admin-edit', compact('hotel'));
    }
}
