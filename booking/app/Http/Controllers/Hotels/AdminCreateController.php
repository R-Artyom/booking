<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Facility;

class AdminCreateController extends Controller
{
    // Форма создания отеля
    public function __invoke()
    {
        // Добавить данные о всех доступных удобствах
        $facilities = Facility::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // Шаблон формы создания отеля
        return view('hotels.admin-create', compact('facilities'));
    }
}
