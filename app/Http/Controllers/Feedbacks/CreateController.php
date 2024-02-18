<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class CreateController extends Controller
{
    // Форма создания отзыва
    public function __invoke(Hotel $hotel)
    {
        // Проверка прав пользователя
//        $this->authorize('create', Feedback::class);

        // Шаблон формы создания
        return view('feedbacks.create', compact('hotel'));
    }
}
