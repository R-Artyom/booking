<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;

class AdminCreateController extends Controller
{
    // Форма создания номера отеля
    public function __invoke(Hotel $hotel)
    {
        // Проверка прав пользователя
        $this->authorize('create', [Room::class, $hotel]);

        // Добавить данные об удобствах
        $facilities = Facility::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // Шаблон формы создания номера
        return view('rooms.admin-create', compact('hotel', 'facilities'));
    }
}
