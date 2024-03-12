<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class AdminDisapproveController extends Controller
{
    // Редактирование отзыва
    public function __invoke(Feedback $feedback)
    {
        // Проверка прав пользователя
        $this->authorize('update', $feedback);

        // Флаг активности отзыва - "Отклонён"
        $feedback->is_active = false;
        // Обновление записи в таблице 'feedbacks'
        $feedback->save();

        // Предыдущая страница
        return back();
    }
}
