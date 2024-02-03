<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Hotel;

class AdminEditController extends Controller
{
    // Форма редактирования отеля
    public function __invoke(Hotel $hotel)
    {
        // Проверка прав пользователя
        $this->authorize('update', $hotel);

        // Добавить данные о всех удобствах
        $facilities = Facility::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // Удобства номера
        $checkedFacilities = [];
        foreach ($hotel->facilities as $facility) {
            $checkedFacilities[$facility->id] = $facility->id;
        }

        // Шаблон редактирования отеля
        return view('hotels.admin-edit', compact('hotel', 'facilities', 'checkedFacilities'));
    }
}
