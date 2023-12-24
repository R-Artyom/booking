<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // Формат данных для фильтрации
    const FILTERS_FORMAT = [
        // Фильтры "ПО ЗНАЧЕНИЮ" - перечислить все ожидаемые поля с опциями фильтрации
        'filtersByIn' => [
            'id', // Id отеля
            'facilityId', // Id удобства отеля
            'roomFacilityId', // Id удобства номера
        ],
        // Фильтры "ПО ШАБЛОНУ" в любой позиции - перечислить все ожидаемые поля без опций (Не должны совпадать с 'filtersByIn')
        'filtersByLike' => [],
    ];

    // Формат данных для словарей - перечислить поля, для которых необходим словарь (с названием словаря в качестве значения поля)
    const DICTIONARIES_FORMAT = [
        'id' => 'hotel', // Отель
        'facilityId' => 'facility', // Удобство отеля
        'roomFacilityId' => 'roomFacility', // Удобство номера
    ];

    // Список отелей
    public function __invoke(Request $request)
    {
        // Сформировать данные для фронта и для бэка
        $helper = (new HelperController());
        $helper->setIndexData($request);
        $indexData = $helper->indexData;

        // Данные о выбранном отеле
        $filterHotel = Hotel::where('id', $indexData['filters']['filterByHotelId'])->first();
        $indexData['filters']['hotel'] = [
            'id' => $filterHotel->id ?? null,
            'name' => $filterHotel->name ?? null,
        ];
        // Данные о выбранном удобстве отеля
        $filterFacility = Facility::where('id', $indexData['filters']['filterByFacilityId'])->first();
        $indexData['filters']['facility'] = [
            'id' => $filterFacility->id ?? null,
            'name' => $filterFacility->name ?? null,
        ];
        // Данные о выбранном удобстве номера
        $filterRoomFacility = Facility::where('id', $indexData['filters']['filterByRoomFacilityId'])->first();
        $indexData['filters']['roomFacility'] = [
            'id' => $filterRoomFacility->id ?? null,
            'name' => $filterRoomFacility->name ?? null,
        ];

        // * Инициализация построителя запроса
//        $hotelsBuilder = Hotel::with(['facilities', 'rooms'])->whereNotNull('id');
        // Фильтрация по цене
        $min = $indexData['filterByMinPrice'];
        $max = $indexData['filterByMaxPrice'];
        $startDay = $indexData['startDate'];
        $endDay = $indexData['endDate'];
        $hotelsBuilder = Hotel::whereHas('rooms', function (Builder $query) use ($min, $max, $startDay, $endDay) {
            // Если пришел фильтр только по максимальной цене
            if (!isset($min) && isset($max)) {
                $query->where('price', '<=', $max);
            // Если пришел фильтр только по минимальной цене
            } elseif (isset($min) && !isset($max)) {
                $query->where('price', '>=', $min);
            // Если пришел фильтр и по минимальной и по максимальной цене
            } elseif (isset($min) && isset($max)) {
                $query->whereBetween('price', [$min, $max]);
            }
            $query->where(function ($query) use ($startDay, $endDay) {
                $query->whereHas('bookings', function (Builder $query) use ($startDay, $endDay) {
                    // Если пришла только дата выезда
                    if (!isset($startDay) && isset($endDay)) {
                        $query->where('started_at', '>=', $endDay);
                    // Если пришла дата заезда
                    } elseif (isset($startDay) && !isset($endDay)) {
                        $query->where('finished_at', '<=', $startDay);
                    // Если пришли обе даты
                    } elseif (isset($startDay) && isset($endDay)) {
                        $query->where('started_at', '>=', $endDay)
                            ->orWhere('finished_at', '<=', $startDay);
                    }
                });
                $query->orDoesntHave('bookings');
            });
        });

        // * Поиск опций фильтрации и словарей для select
        $hotelsCollect = $hotelsBuilder->get();
        // Результирующий массив
        $result = [];
        $dictionariesAll = [];
        $dictionariesAll['roomFacility'] = [];
        foreach ($hotelsCollect as $hotel) {
            // * Все удобства номеров отеля
            $roomFacilityIds = [];
            foreach ($hotel->rooms as $room) {
                // Слить массивы удобств номеров в один
                $roomFacilityIds = array_merge($roomFacilityIds, $room->facilities->pluck('id', 'name')->toArray());
            }
            // Только уникальные
            $roomFacilityIds = array_unique($roomFacilityIds);

            // * Результирующий массив
            $result[] = [
                // Id отеля
                'id' => $hotel->id,
                // Удобства отеля
                'facilityId' => $hotel->facilities->pluck('id')->toArray(),
                // Удобства номера
                'roomFacilityId' => $roomFacilityIds,
                // Минимальная цена за номер
                'minPrice' => $hotel->rooms->min('price'),
                // Максимальная цена за номер
                'maxPrice' => $hotel->rooms->max('price'),
            ];

            // * Полный словарь
            // Отель
            $dictionariesAll['hotel'][$hotel->id] = $hotel->name;
            // Удобства отеля
            foreach ($hotel->facilities as $facility) {
                $dictionariesAll['facility'][$facility->id] = $facility->name;
            }
            // Удобства номеров
            $dictionariesAll['roomFacility'] = array_merge($dictionariesAll['roomFacility'], $roomFacilityIds);
        }
        // Корректировка словаря удобств номеров
        $dictionariesAll['roomFacility'] = array_flip($dictionariesAll['roomFacility']);

        // Преобразовать результирующий массив в коллекцию
        $hotelsCollect = collect($result);

        // Опции фильтрации по полям
        $indexData['filterOptions'] = $this->getOptionsForFilters($hotelsCollect, $helper->backendIndexData['filters'] ?? null, self::FILTERS_FORMAT);
        // Фильтрация
        $hotelsCollect = $this->filterCollection($hotelsCollect, $helper->backendIndexData['filters'] ?? null, self::FILTERS_FORMAT);
        // Словари
        $indexData['dictionaries'] = $this->getFilterOptionsDictionaries($indexData['filterOptions'], $dictionariesAll, self::DICTIONARIES_FORMAT);

        // Минимальная цена за номер из всех отфильтрованных
        $indexData['minPrice'] = $hotelsCollect->min('minPrice');
        // Максимальная цена за номер из всех отфильтрованных
        $indexData['maxPrice'] = $hotelsCollect->max('maxPrice');

        // Итоговый список id отелей
        $hotelIds = $hotelsCollect->pluck('id')->toArray();
        // * Выборка необходимых отелей - то же самое, что и итоговая фильтрация
        $hotelsBuilder->whereIn('id', $hotelIds);

        // * Сортировка
        $hotelsBuilder->orderBy($helper->backendIndexData['sort']['field'], $helper->backendIndexData['sort']['order']);

        // * Пагинация
        $hotels = $hotelsBuilder->paginate(6);

        // Диапазон цен за 1 ночь
        $hotels->map(function($hotel) {
            // Минимальная цена за номер
            $minPrice = $hotel->rooms->min('price');
            // Максимальная цена за номер
            $maxPrice = $hotel->rooms->max('price');
            // Если они равны, то итог - любая одна
            if ($minPrice === $maxPrice) {
                $price = $minPrice;
            // Если цены разные - то диапазон
            } else {
                $price = $minPrice . '-' . $maxPrice;
            }
            // Новое поле в каждом элементе коллекции
            $hotel['price'] = $price;
            return $hotel;
        });

        // Шаблон отелей
        return view('hotels.index', compact('hotels', 'indexData'));
    }
}
