<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;
use App\Models\Facility;

class AdminEditController extends Controller
{
    // Форма редактирования удобства
    public function __invoke(Facility $facility)
    {
        // Шаблон редактирования удобства
        return view('facilities.admin-edit', compact('facility'));
    }
}
