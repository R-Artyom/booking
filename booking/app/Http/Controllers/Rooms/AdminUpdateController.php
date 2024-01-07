<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUpdateController extends Controller
{
    // Редактирование данных номера отеля
    public function __invoke(Request $request, Room $room)
    {
        // Валидация
        $newData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'floorArea' => 'required|numeric|min:0|max:999999.99',
            'type' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => [
                'image',
                'mimetypes:' . config('image.allowed_mime_types'),
                'max:'. config('image.max_size'),
            ],
            // Удобства номера
            'checkedFacilities' => 'array',
            'checkedFacilities.*' => 'distinct|integer|numeric|exists:facilities,id',
        ],
        [
            // Название
            'name.required' => 'Название номера не может быть пустым',
            'name.string' => 'Название номера должно быть строкой',
            'name.max' => 'Название номера не должно превышать 100 символов',
            // Описание
            'description.required' => 'Описание отеля не может быть пустым',
            'description.string' => 'Описание отеля должно быть строкой',
            // Площадь
            'floorArea.required' => 'Площадь номера не может быть пустой',
            'floorArea.numeric' => 'Площадь номера должна иметь корректное числовое или дробное значение',
            'floorArea.min' => 'Площадь номера не должна быть отрицательной',
            'floorArea.max' => 'Площадь номера не должна превышать значение 999999.99',
            // Тип
            'type.required' => 'Тип номера не может быть пустым',
            'type.string' => 'Тип номера должен быть строкой',
            'type.max' => 'Тип номера не должен превышать 100 символов',
            // Цена
            'price.required' => 'Цена не может быть пустой',
            'price.numeric' => 'Цена должна иметь корректное числовое или дробное значение',
            'price.min' => 'Цена не должна быть отрицательной',
            'price.max' => 'Цена не должна превышать значение 999999.99',
            // Изображение
            'image.image' => 'Загружаемый файл должен быть изображением',
            'image.mimetypes' => 'Тип файла не поддерживается',
            'image.max' => 'Размер файла не должен превышать ' . config('image.max_size') . ' КБ',
            // Удобства номера
            'checkedFacilities.*.distinct' => 'В списке удобств не должно быть повторяющихся значений',
            'checkedFacilities.*.integer' => 'Номер удобства должен иметь корректное целочисленное значение',
            'checkedFacilities.*.numeric' => 'Номер удобства должен иметь корректное числовое или дробное значение',
            'checkedFacilities.*.exists' => 'Удобства с таким номером нет в списке разрешенных',
        ]);

        // Ссылка на изображение в обход аксессора
        $originalPosterUrl = $room->getRawOriginal('poster_url');

        // Название номера отеля
        if (!empty($newData['name'])) {
            $room->name = $newData['name'];
        }
        // Описание номера
        if (!empty($newData['description'])) {
            $room->description = $newData['description'];
        }
        // Площадь номера
        if (!empty($newData['floorArea'])) {
            $room->floor_area = $newData['floorArea'];
        }
        // Тип
        if (!empty($newData['type'])) {
            $room->type = $newData['type'];
        }
        // Цена
        if (!empty($newData['price'])) {
            $room->price = $newData['price'];
        }
        // Ссылка на изображение
        if (!empty($newData['image'])) {
            // Если есть что загружать
            if ($request->hasFile('image')) {
                // Загрузка файла на сервер storage/public/rooms/...
                $posterUrl = $request->image->store('rooms', 'public');
                // Сохранение нового значения
                $room->poster_url = $posterUrl;
            }
        }

        // Если были какие-либо изменения в модели (атрибуты, которые были изменены с момента последней синхронизации (attributes VS original))
        if ($room->isDirty()) {
            // * Обновление записи в таблице 'rooms'
            $room->save();
        }

        // Если была изменена ссылка на изображение
        if ($room->wasChanged('poster_url')) {
            // Удалить старое изображение
            Storage::disk('public')->delete($originalPosterUrl);
        }

        // Синхронизировать удобства номера в сводной таблице 'facility_room' (удалить ненужные, добавить нужные)
        $room->facilities()->sync($newData['checkedFacilities'] ?? []);

        // Страница редактирования номера отеля
        return back()->withInput()->with('success', 'Данные успешно отредактированы!');
    }
}
