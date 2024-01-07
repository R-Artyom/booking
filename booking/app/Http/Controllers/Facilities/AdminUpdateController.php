<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class AdminUpdateController extends Controller
{
    // Редактирование данных удобства
    public function __invoke(Request $request, Facility $facility)
    {
        // Валидация
        $newData = $request->validate([
            'name' => 'required|string|max:100',
        ],
        [
            // Название
            'name.required' => 'Название номера не может быть пустым',
            'name.string' => 'Название номера должно быть строкой',
            'name.max' => 'Название номера не должно превышать 100 символов',
        ]);

        // Название номера отеля
        if (!empty($newData['name'])) {
            $facility->name = $newData['name'];
        }

        // Если были какие-либо изменения в модели (атрибуты, которые были изменены с момента последней синхронизации (attributes VS original))
        if ($facility->isDirty()) {
            // * Обновление записи в таблице
            $facility->save();
        }

        // Страница редактирования номера отеля
        return back()->withInput()->with('success', 'Данные успешно отредактированы!');
    }
}
