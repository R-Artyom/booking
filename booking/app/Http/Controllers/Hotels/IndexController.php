<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // Список отелей
    public function __invoke()
    {
        // Отели с удобствами и комнатами
        $hotels = Hotel::with(['facilities', 'rooms'])->get();
        // Диапазон цен за 1 ночь
        $hotels->map(function ($hotels) {
            // Минимальная цена за номер
            $minPrice = $hotels->rooms->min('price');
            // Максимальная цена за номер
            $maxPrice = $hotels->rooms->max('price');
            // Если они равны, то итог - любая одна
            if ($minPrice === $maxPrice) {
                $price = $minPrice;
            // Если цены разные - то диапазон
            } else {
                $price = $minPrice . '-' . $maxPrice;
            }
            // Новое поле в каждом элементе коллекции
            $hotels['price'] = $price;
            return $hotels;
        });

        // Шаблон отелей
        return view('hotels.index', compact('hotels'));
    }
}
