<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;

class AdminDestroyController extends Controller
{
    // Удаление отеля
    public function __invoke(Hotel $hotel)
    {
        // Проверка прав пользователя
        $this->authorize('delete', $hotel);

        // Ссылка на изображение в обход аксессора
        $originalPosterUrl = $hotel->getRawOriginal('poster_url');
        // Удаление изображения отеля
        Storage::disk('public')->delete($originalPosterUrl);

        // Удаление модели
        $hotel->delete();

        // Предыдущая страница для перенаправления после удаления (или данные из сессии или предыдущая страница (если в сессии нет))
        $previousUrl = request()->session()->get('pre_previous_url', url()->previous());

        // Последняя страница, до удаления
        return redirect()->to($previousUrl);
    }
}
