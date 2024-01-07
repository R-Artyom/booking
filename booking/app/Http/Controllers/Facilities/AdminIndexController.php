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
        // Данные о всех доступных удобствах
        $facilities = Facility::query()
            ->select('id', 'name', 'created_at')
            ->orderBy('id', 'desc')
            ->get();

        // Шаблон
        return view('facilities.admin-index', compact('facilities'));
    }
}
