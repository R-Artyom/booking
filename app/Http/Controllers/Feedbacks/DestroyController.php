<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class DestroyController extends Controller
{
    // Удаление отзыва
    public function __invoke(Feedback $feedback)
    {
        // Проверка прав пользователя
//        $this->authorize('delete', $room);

        // Удаление модели
        $feedback->delete();

        // Текущая страница
        return back();
    }
}
