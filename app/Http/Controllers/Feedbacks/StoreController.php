<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Hotel;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // Создание отзыва
    public function __invoke(Request $request, Hotel $hotel)
    {
        // Проверка прав пользователя
//        $this->authorize('create', [Feedback::class, $hotel]);

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

        $feedback = new Feedback();

        // Юзер
        $feedback->user_id = auth()->user()->id;
        // Отель
        $feedback->hotel_id = $hotel->id;
        // Текст отзыва
        $feedback->text = $newData['text'];
        // Оценка отелю
        $feedback->rating = $newData['rating'];

        // Создание отзыва
        $feedback->save();

        // Страница просмотра всех отзывов
        return redirect()->route('feedbacks.index', compact('hotel'));
    }
}
