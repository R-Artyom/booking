<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUpdateController extends Controller
{
    // Редактирование данных пользователя
    public function __invoke(Request $request, User $user)
    {
        // Проверка прав пользователя
        $this->authorize('update', $user);

        // Валидация
        $newData = $request->validate([
            // Роли пользователя
            'roleNames' => 'array',
            'roleNames.*' => 'distinct|string|exists:roles,name',
        ],
        [
            // Роли пользователя
            'roleNames.*.distinct' => 'В списке ролей не должно быть повторяющихся значений',
            'roleNames.*.string' => 'Название роли должно быть строкой',
            'roleNames.*.exists' => 'Роли с таким названием нет в списке разрешенных',
        ]);

        // Синхронизировать роли пользователя в таблице 'role_user' (удалить ненужные, добавить нужные)
        $user->roles()->sync($newData['roleNames'] ?? []);

        // Страница списка пользователей
        return back();
    }
}
