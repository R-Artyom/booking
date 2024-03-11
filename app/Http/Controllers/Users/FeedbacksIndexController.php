<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;

class FeedbacksIndexController extends Controller
{
    // Список отзывов
    public function __invoke(User $user)
    {
        // Проверка прав пользователя
//        $this->authorize('viewAny', Feedback::class);

        // Данные о всех отзывах пользователя
        $feedbacksBuilder = Feedback::query()
            ->where('user_id', $user->id)
            // 1. Сортировака по признаку одобрения (чтобы сначала были отзывы тек. пользователя)
            ->orderBy('is_active', 'asc')
            // 2. Сортировака по убыванию номера отзыва
            ->orderBy('id', 'desc');

        // Отзывы с пагинацией
        $feedbacks = $feedbacksBuilder->paginate(5);

        // Шаблон
        return view('users.feedbacks-index', compact('feedbacks'));
    }
}
