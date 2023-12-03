<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUpdateController extends Controller
{
    // Отмена бронирования
    public function __invoke(Request $request, Hotel $hotel)
    {
        // Валидация
        $newData = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'description' => 'required|string',
            'image' => [
                'image',
                'mimetypes:' . config('image.allowed_mime_types'),
                'max:'. config('image.max_size'),
            ],
        ],
        [
            'name.required' => 'Название отеля не может быть пустым',
            'name.string' => 'Название отеля должно быть строкой',
            'name.max' => 'Название отеля не должно превышать 100 символов',
            'address.required' => 'Адрес отеля не может быть пустым',
            'address.string' => 'Адрес отеля должен быть строкой',
            'address.max' => 'Адрес отеля не должно превышать 500 символов',
            'description.required' => 'Описание отеля не может быть пустым',
            'description.string' => 'Описание отеля должно быть строкой',
            'image.image' => 'Загружаемый файл должен быть изображением',
            'image.mimes' => 'Тип файла не поддерживается',
            'image.max' => 'Размер файла не должен превышать ' . config('image.max_size') . ' КБ',
        ]);

        // Ссылка на изображение в обход аксессора
        $originalPosterUrl = $hotel->getRawOriginal('poster_url');

        // Название отеля
        if (!empty($newData['name'])) {
            $hotel->name = $newData['name'];
        }

        // Адрес отеля
        if (!empty($newData['address'])) {
            $hotel->address = $newData['address'];
        }

        // Описание отеля
        if (!empty($newData['description'])) {
            $hotel->description = $newData['description'];
        }

        // Адрес отеля
        if (!empty($newData['image'])) {
            // Если есть что загружать
            if ($request->hasFile('image')) {
                // Загрузка файла на сервер storage/public/hotels/...
                $posterUrl = $request->image->store('hotels', 'public');
                // Сохранение нового значения
                $hotel->poster_url = $posterUrl;
            }
        }

        // Если были какие-либо изменения в модели (атрибуты, которые были изменены с момента последней синхронизации (attributes VS original))
        if ($hotel->isDirty()) {
            // * Обновление записи в таблице 'reminders'
            $hotel->save();
        }

        // Если была изменена ссылка на изображение
        if ($hotel->wasChanged('poster_url')) {
            // Удалить старое изображение
            Storage::disk('public')->delete($originalPosterUrl);
        }

        // Страница редактирования отеля
        return back()->withInput()->with('success', 'Данные успешно отредактированы!');
    }
}
