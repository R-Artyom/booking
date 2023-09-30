<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StoreController extends Controller
{
    // Сохранение бронирования
    public function __invoke(Request $request)
    {
        $validated = $this->validate($request, [
            // Дата начала заезда
            'started_at' => 'required',
            // Дата окончания заезда
            'finished_at' => 'required|after:started_at',
            // Id номера отеля
            'room_id' => 'required',
        ],
        [
            'started_at.required' => 'Введите дату начала заезда',
            'finished_at.required' => 'Введите дату окончания заезда',
            'finished_at.after' => 'Дата выезда должна быть позже даты заезда, выберите диапазон дат и нажмите "Загрузить номера"',
            'room_id.required' => 'Укажите номер отеля',
        ]);

        // Преобразование даты из 'd-m-Y' или любой другой в 'Y-m-d H:i:s' (в частности 'Y-m-d 12:00:00')
        $validated['started_at'] = (new DateTime($validated['started_at']))->setTime(12, 0)->format('Y-m-d');
        // Преобразование даты из 'd-m-Y' или любой другой в 'Y-m-d H:i:s' (в частности 'Y-m-d 10:00:00')
        $validated['finished_at'] = (new DateTime($validated['finished_at']))->setTime(10, 0)->format('Y-m-d');

        // Данные запрашиваемого номер отеля
        $room = Room::with('bookings')->where('id', $validated['room_id'])->first();

        // * Проверка возможности бронирования номера отеля на указанный диапазон дат
        // Цикл по всем бронированиям номера
        foreach ($room->bookings as $booking) {
            // Все варианты, когда номер в данном диапазоне дат считается занятым
            if (($validated['started_at'] >= $booking->started_at && $validated['started_at'] < $booking->finished_at)
                || ($validated['finished_at'] > $booking->started_at && $validated['finished_at'] <= $booking->finished_at)
                || ($validated['started_at'] < $booking->started_at && $validated['finished_at'] > $booking->finished_at)) {
                // Выброс исключения - бронирование невозможно
                throw ValidationException::withMessages(['Номер занят, выберите другую дату и нажмите "Загрузить номера"']);
            }
        }

        // * Бронирование возможно, дальнейшие действия:
        // Количество дней бронирования
        $secondsNumber = abs(strtotime($validated['finished_at']) - strtotime($validated['started_at']));
        $daysNumber = round($secondsNumber / 86400);

        // Цена за 1 ночь
        $roomPrice = $room->price;

        // Сохранение бронирования
        $booking = Booking::create([
            // Id номера
            'room_id' => $validated['room_id'],
            // Id пользователя
            // TODO после введения авторизации строку раскомментировать, а хардкод удалить
            //'user_id' => auth()->user()->id,
            'user_id' => 1,
            // Дата заезда
            'started_at' => $validated['started_at'],
            // Дата выезда
            'finished_at' => $validated['finished_at'],
            // Количество дней
            'days' => $daysNumber,
            // Цена за все дни
            'price' => $roomPrice * $daysNumber,
        ]);

        // Страница просмотра бронирования
        return view('bookings.show', compact('booking'));
    }
}
