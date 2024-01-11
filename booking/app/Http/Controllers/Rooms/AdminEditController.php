<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;

class AdminEditController extends Controller
{
    // Форма редактирования номера отеля
    public function __invoke(Room $room)
    {
        // Проверка прав пользователя
        $this->authorize('update', $room);

        // Добавить данные о всех удобствах
        $facilities = Facility::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // Удобства номера
        $checkedFacilities = [];
        foreach ($room->facilities as $facility) {
            $checkedFacilities[$facility->id] = $facility->id;
        }

        // Шаблон редактирования номера отеля
        return view('rooms.admin-edit', compact('room', 'facilities', 'checkedFacilities'));
    }
}
