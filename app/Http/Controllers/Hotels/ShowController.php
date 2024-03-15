<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    // Просмотр отеля
    public function __invoke(Hotel $hotel)
    {
        // Валидация GET-параметров запроса
        $validated = $this->validate(request(), [
            // Дата заезда
            'start_date' => '',
            // Дата выезда
            'end_date' => 'after:start_date',
        ],
        [
            'end_date.after' => 'Дата выезда должна быть позже даты заезда, выберите диапазон дат и нажмите "Загрузить номера"',
        ]);

        // Номера отеля
        $rooms = $hotel->rooms()->with('bookings')->get();

        // Если это запрос методом GET, то показывать только свободные номера отеля в диапазоне запрашиваемых дат
        if (isset($validated['start_date']) && isset($validated['end_date'])) {
            // Дата с
            $startDate = $validated['start_date'];
            // Дата по
            $endDate = $validated['end_date'];

            // Фильтр номеров по возможности бронирования (отображать только доступные)
            $rooms = $rooms->filter(function ($room, $key) use($startDate, $endDate) {
                // Цикл по всем бронированиям номера
                foreach ($room->bookings as $booking) {
                    // Все варианты, когда номер в данном диапазоне дат считается занятым
                    if (($startDate >= $booking->started_at && $startDate < $booking->finished_at)
                        || ($endDate > $booking->started_at && $endDate <= $booking->finished_at)
                        || ($startDate < $booking->started_at && $endDate > $booking->finished_at)) {
                        // Номер занят
                        $roomIsBusy = true;
                        // Переход к следующему номеру отеля
                        break;
                    }
                }
                // Количество дней бронирования номера
                $secondsNumber = abs(strtotime($endDate) - strtotime($startDate));
                $room['total_days'] = round($secondsNumber / 86400);
                // Цена за все дни
                $room['total_price'] = $room['total_days'] * $room->price;
                // Если номер занят - то исключается из коллекции
                return !isset($roomIsBusy);
            });
        // Если не указан диапазон дат, то указать цену за сутки
        } else {
            // Фильтр номеров по возможности бронирования
            $rooms = $rooms->map(function ($room, $key) {
                // Количество дней бронирования номера = 1
                $room['total_days'] = 1;
                // Цена за 1 ночь
                $room['total_price'] = $room->price;
                // Доступны все номера, т.к. не было запроса дат
                return $room;
            });
        }

        // Если нет ни одного свободного номера
        if ($rooms->isEmpty()) {
            // Шаблон отеля с ошибкой
            return view('hotels.show', compact('hotel', 'rooms'))->withErrors(['В указанном диапазоне дат все номера заняты']);
        } else {
            // Шаблон отеля
            return view('hotels.show', compact('hotel', 'rooms'));
        }
    }
}
