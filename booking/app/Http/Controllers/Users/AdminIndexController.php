<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminIndexController extends Controller
{
    // Список удобств
    public function __invoke(Request $request)
    {
        // Данные о всех пользователях
        $users = User::query()
            ->select(
                'id',
                'name',
                'email',
                'created_at'
            )
            ->orderBy('id', 'asc')
            ->paginate(50);

        // Шаблон
        return view('users.admin-index', compact('users'));
    }
}
