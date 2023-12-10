<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    // Создание отеля
    public function __invoke(Request $request)
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
            'image.mimetypes' => 'Тип файла не поддерживается',
            'image.max' => 'Размер файла не должен превышать ' . config('image.max_size') . ' КБ',
        ]);

        $hotel = new Hotel;

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

        // Ссылка на изображение
        if (!empty($newData['image'])) {
            // Если есть что загружать
            if ($request->hasFile('image')) {
                // Загрузка файла на сервер storage/public/hotels/...
                $posterUrl = $request->image->store('hotels', 'public');
                // Сохранение нового значения
                $hotel->poster_url = $posterUrl;
            }
        }

        // Создание отеля
        $hotel->save();

        // Страница просмотра отеля
        return redirect()->route('admin.hotels.show', compact('hotel'));
    }
}
