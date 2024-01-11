<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;
use App\Models\Facility;

class AdminCreateController extends Controller
{
    // Форма создания удобства
    public function __invoke()
    {
        // Проверка прав пользователя
        $this->authorize('create', Facility::class);

        // Шаблон формы создания удобства
        return view('facilities.admin-create');
    }
}
