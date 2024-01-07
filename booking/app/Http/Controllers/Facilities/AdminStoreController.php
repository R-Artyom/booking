<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    // Создание отеля
    public function __invoke(Request $request)
    {
        // Валидация
        $newData = $request->validate([
            'name' => 'required|string|max:100',
        ],
        [
            'name.required' => 'Название отеля не может быть пустым',
            'name.string' => 'Название отеля должно быть строкой',
            'name.max' => 'Название отеля не должно превышать 100 символов',
        ]);

        $facility = new Facility();

        // Название удобства
        if (!empty($newData['name'])) {
            $facility->name = $newData['name'];
        }

        // Создание удобства
        $facility->save();

        // Страница просмотра всех удобств
        return redirect()->route('admin.facilities.index');
    }
}
