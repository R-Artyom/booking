<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;

class AdminCreateController extends Controller
{
    // Форма создания удобства
    public function __invoke()
    {
        // Шаблон формы создания удобства
        return view('facilities.admin-create');
    }
}
