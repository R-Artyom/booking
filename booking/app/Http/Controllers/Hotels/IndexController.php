<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // Список отелей
    public function __invoke()
    {
        // Отели
        $hotels = Hotel::all();
        // Шаблон отелей
        return view('hotels.index', compact('hotels'));
    }

}
