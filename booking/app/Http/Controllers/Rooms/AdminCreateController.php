<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Hotel;

class AdminCreateController extends Controller
{
    // Форма создания номера отеля
    public function __invoke(Hotel $hotel)
    {
        // Добавить данные об удобствах
        $facilities = Facility::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // Шаблон формы создания номера
        return view('rooms.admin-create', compact('hotel', 'facilities'));
    }
}
