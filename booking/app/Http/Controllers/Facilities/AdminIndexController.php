<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class AdminIndexController extends Controller
{
    // Список удобств
    public function __invoke(Request $request)
    {
        // Проверка прав пользователя
        $this->authorize('viewAny', Facility::class);

        // Данные о всех доступных удобствах
        $facilities = Facility::query()
            ->select('id', 'name', 'created_at')
            ->orderBy('id', 'desc')
            ->paginate(50);

        // Шаблон
        return view('facilities.admin-index', compact('facilities'));
    }
}
