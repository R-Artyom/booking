<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Hotel;

class IndexController extends Controller
{
    // Список отзывов отеля
    public function __invoke(Hotel $hotel)
    {
        // Проверка прав пользователя
        $this->authorize('viewAny', Feedback::class);

        // Данные о всех отзывах на отель
        $feedbacksBuilder = Feedback::query()
            ->where('hotel_id', $hotel->id)
            ->where(function ($query) {
                // Одобренные
                $query->where('is_active', 1);
                // Или принадлежат текущему юзеру
                $query->orWhere('user_id', auth()->user()->id);
            })
            // 1. Сортировака по признаку одобрения (чтобы сначала были отзывы тек. пользователя)
            ->orderBy('is_active', 'asc')
            // 2. Сортировака по убыванию номера отзыва
            ->orderBy('id', 'desc');

        // Коллекция отзывов
        $feedbacksCollect = $feedbacksBuilder->get();
        // Признак "Добавление отзыва заблокировано" (отзыв на текущий отель у тек. пользователя уже есть)
        $feedbackAddLock = $feedbacksCollect->containsStrict('is_active', 0);

        // Отзывы с пагинацией
        $feedbacks = $feedbacksBuilder->paginate(50);

        // Шаблон
        return view('feedbacks.index', compact('feedbacks', 'hotel', 'feedbackAddLock'));
    }
}
