<?php

namespace App\Http\Controllers\Feedbacks;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminIndexController extends Controller
{
    // Список отзывов
    public function __invoke(Request $request)
    {
        // Проверка прав пользователя
        $this->authorize('viewAny', Feedback::class);

        // Данные о всех отзывах
        $feedbacksBuilder = Feedback::query();

        // У менеджера отеля доступ есть только к своим отелям (у админа - к любому отелю)
        if (isManager(auth()->user())) {
            // Список id отелей менеджера
            $managerHotelIds = auth()->user()->hotels->pluck('id')->toArray();
            // Отзывы только для отелей из списка
            $feedbacksBuilder->whereIn('hotel_id', $managerHotelIds);
        }
        // 1. Сортировака по признаку одобрения (чтобы сначала были отзывы тек. пользователя)
        $feedbacksBuilder->orderBy('is_active', 'asc')
        // 2. Сортировака по возрастанию даты создания отзыва
        ->orderBy('created_at', 'asc');

        // Отзывы с пагинацией
        $feedbacks = $feedbacksBuilder->paginate(5);

        // Шаблон
        return view('feedbacks.admin-index', compact('feedbacks'));
    }
}
