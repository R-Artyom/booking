<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class AdminIndexController extends Controller
{
    // Формат данных для фильтрации
    const FILTERS_FORMAT = [
        // Фильтры "ПО ЗНАЧЕНИЮ" - перечислить все ожидаемые поля с опциями фильтрации
        'filtersByIn' => [
            'hotelId', // Id отеля
            'userId', // Id инициатора
        ],
        // Фильтры "ПО ШАБЛОНУ" в любой позиции - перечислить все ожидаемые поля без опций (Не должны совпадать с 'filtersByIn')
        'filtersByLike' => [],
    ];

    // Формат данных для словарей - перечислить поля, для которых необходим словарь (с названием словаря в качестве значения поля)
    const DICTIONARIES_FORMAT = [
        'hotelId' => 'hotel', // Отель
        'userId' => 'user', // Пользователь
    ];

    // Список бронирований Админ панели
    public function __invoke(Request $request)
    {
        // Проверка прав пользователя - доступно только админу и менеджеру отеля
        $this->authorize('viewAny', Booking::class);

        // Текущий пользователь
        $user = auth()->user();
        // Текущий день с самого начала
        $currTime = now()->format('Y-m-d');

        // Сформировать данные для фронта и для бэка
        $helper = (new HelperController());
        $helper->setIndexData($request);
        $indexData = $helper->indexData;

        // Данные о выбранном отеле
        $filterHotel = Hotel::where('id', $indexData['filters']['filterByHotel'])->first();
        $indexData['filters']['hotel'] = [
            'id' => $filterHotel->id ?? null,
            'name' => $filterHotel->name ?? null,
        ];
        // Данные о выбранном пользователе
        $filterUser = User::where('id', $indexData['filters']['filterByUser'])->first();
        $indexData['filters']['user'] = [
            'id' => $filterUser->id ?? null,
            'name' => $filterUser->name ?? null,
        ];

        // * Инициализация построителя запросов
        $bookingsBuilder = Booking::whereNotNull('id');
        // * Фильтры по датам
        // Не показывать завершённые
        if ($helper->backendIndexData['showOld'] === false) {
            $bookingsBuilder->where('finished_at', '>=', $currTime);
        }

        // * Для менеджера отеля доступ есть только к бронированиям своего отеля, для админа - любого отеля
        if ($user->roles->containsStrict('name', 'manager')) {
            // Список id отелей менеджера
            $hotelIds = $user->hotels->pluck('id')->toArray();
            // Список номеров
            $roomIds = Room::whereIn('hotel_id', $hotelIds)->get()->pluck('id')->toArray();
            // Бронирования отелей менеджера и собственные бронирования менеджера
            $bookingsBuilder->whereIn('room_id', $roomIds)->orWhere('user_id', $user->id);
        }

        // * Поиск опций фильтрации и словарей для select
        $bookingsCollect = $bookingsBuilder->get();
        // Результирующий массив
        $result = [];
        $dictionariesAll = [];
        foreach ($bookingsCollect as $booking) {
            $result[] = [
                // Id бронирования
                'id' => $booking->id,
                // Id отеля
                'hotelId' => $booking->room->hotel_id,
                // Id пользователя
                'userId' => $booking->user_id,
            ];
            $dictionariesAll['hotel'][$booking->room->hotel_id] = $booking->room->hotel->name;
            $dictionariesAll['user'][$booking->user->id] = $booking->user->name;
        }
        // Преобразовать результирующий массив в коллекцию
        $bookingsCollect = collect($result);
        // Опции фильтрации по полям
        $indexData['filterOptions'] = $this->getOptionsForFilters($bookingsCollect, $helper->backendIndexData['filters'] ?? null, self::FILTERS_FORMAT);
        // Фильтрация
        $bookingsCollect = $this->filterCollection($bookingsCollect, $helper->backendIndexData['filters'] ?? null, self::FILTERS_FORMAT);
        // Словари
        $indexData['dictionaries'] = $this->getFilterOptionsDictionaries($indexData['filterOptions'], $dictionariesAll, self::DICTIONARIES_FORMAT);
        // Итоговый список id бронирований
        $bookingIds = $bookingsCollect->pluck('id')->toArray();

        // * Выборка необходимых бронирований - то же самое, что и итоговая фильтрация
        $bookingsBuilder->whereIn('id', $bookingIds);

        // * Сортировка
        $bookingsBuilder->orderBy($helper->backendIndexData['sort']['field'], $helper->backendIndexData['sort']['order']);

        // * Пагинация
        $bookings = $bookingsBuilder->paginate(5);

        // Шаблон бронирований
        return view('bookings.index', compact('bookings', 'indexData'));
    }
}
