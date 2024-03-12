<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class EditController extends Controller
{
    // Форма редактирования отзыва
    public function __invoke(Feedback $feedback)
    {
        // Проверка прав пользователя
        $this->authorize('update', $feedback);

        // Шаблон редактирования отзыва
        return view('feedbacks.edit', compact('feedback'));
    }
}
