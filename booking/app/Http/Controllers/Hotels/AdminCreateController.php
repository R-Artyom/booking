<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;

class AdminCreateController extends Controller
{
    // Форма создания отеля
    public function __invoke()
    {
        // Шаблон формы создания отеля
        return view('hotels.admin-create');
    }
}
