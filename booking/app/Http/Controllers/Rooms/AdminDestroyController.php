<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class AdminDestroyController extends Controller
{
    // Удаление отеля
    public function __invoke(Room $room)
    {
        // Ссылка на изображение в обход аксессора
        $originalPosterUrl = $room->getRawOriginal('poster_url');
        // Удаление изображения номера отеля
        Storage::disk('public')->delete($originalPosterUrl);

        // Удаление модели
        $room->delete();

        // Текущая страница
        return back();
    }
}
