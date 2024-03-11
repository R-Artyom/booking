<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    // Редактирование отзыва
    public function __invoke(Request $request, Feedback $feedback)
    {
        // Проверка прав пользователя
//        $this->authorize('update', $feedback);

        // Валидация
        $newData = $request->validate(
            [
                'text' => 'required|string|max:5000',
                'rating' => 'required|integer|numeric',
            ],
            [
                // Отзыв
                'text.required' => 'Отзыв не может быть пустым',
                'text.string' => 'Отзыв должен быть строкой',
                'text.max' => 'Отзыв не должен превышать 5000 символов',
                // Оценка
                'rating.required' => 'Поставьте оценку',
                'rating.integer' => 'Оценка должна иметь корректное целочисленное значение',
                'rating.numeric' => 'Оценка должна иметь корректное числовое значение',
            ]
        );

        // Текст отзыва
        $feedback->text = $newData['text'];
        // Оценка отелю
        $feedback->rating = $newData['rating'];

        // Если были какие-либо изменения в модели (атрибуты, которые были изменены с момента последней синхронизации (attributes VS original))
        if ($feedback->isDirty()) {
            // Флаг активности отзыва - "Требуется одобрение"
            $feedback->is_active = false;
            // Обновление записи в таблице 'feedbacks'
            $feedback->save();
        }

        // Страница редактирования отзыва
        return back()->withInput()->with('success', 'Данные успешно отредактированы!');
    }
}
