<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;

class AdminIndexController extends Controller
{
    // Список пользователей
    public function __invoke(Request $request)
    {
        // Проверка прав пользователя
        $this->authorize('viewAny', User::class);

        // * Данные о всех пользователях
        $users = User::query()
            ->select(
                'id',
                'name',
                'email',
                'created_at'
            )
            ->orderBy('id', 'asc')
            ->paginate(50);

        // * Данные о всех отелях для селекта таблицы
        $hotels = Hotel::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // * Данные об отелях, закреплённых за пользователями (для селекта таблицы)
        $userHotelsString = []; // В виде строки (напр, '1,3,10')
        $userHotelsArray = []; // В виде массива (напр, [1,3,10])
        foreach ($users as $user) {
            // Список id отелей пользователя в виде массива
            $ids = [];
            foreach ($user->hotels as $hotel) {
                $ids[] = $hotel->id;
            }
            // Формирование данных для фронта
            if (!empty($ids)) {
                // Список id отелей пользователя в виде массива
                $userHotelsArray[$user->id] = $ids;
                // Список id отелей пользователя в виде строки
                $userHotelsString[$user->id] = implode(",", $ids);
            }
        }

        // Шаблон
        return view('users.admin-index', compact('users', 'hotels', 'userHotelsString', 'userHotelsArray'));
    }
}
