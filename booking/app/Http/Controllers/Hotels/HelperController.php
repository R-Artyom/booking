<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    // Разрешенная сортировка
    const ALLOWED_SORT_TYPES = [
        // По названию отеля
        'name' => [           // Поле из запроса фронта
            'field' => 'id',  // Поле для формирования сортировки в запросе к БД
            'order' => 'asc', // Направление сортировки
        ],
        'nameDesc' => [
            'field' => 'id',
            'order' => 'desc',
        ],
        // По адресу отеля
        'address' => [
            'field' => 'address',
            'order' => 'asc',
        ],
        'addressDesc' => [
            'field' => 'address',
            'order' => 'desc',
        ],
    ];

    // Данные для фронта по умолчанию
    public array $indexData = [
        // Фильтры:
        // Название отеля
        'filterByHotelId' => 0,
        // Удобства отеля
        'filterByFacilityId' => null,
        // Удобства номера
        'filterByRoomFacilityId' => null,
        // Цена от
        'filterByMinPrice' => null,
        // Цена до
        'filterByMaxPrice' => null,
        // Дата заезда
        'startDate' => null,
        // Дата выезда
        'endDate' => null,

        // Опции фильтрации
        'filterOptions' => [],

        // Сортировка по возрастанию названия отеля
        'sort' => 'name',

    ];

    // Данные для бэка по умолчанию
    public array $backendIndexData = [
        // Фильтры
        'filters' => [],
        // Параметры сортировки
        'sort' => [
            'field' => 'name', // Поле для формирования сортировки в запросе к БД
            'order' => 'asc',  // Направление сортировки
        ],
    ];

    /**
     * Формирование данных для шаблона страницы "Список отелей"
     * @param Request $request
     * @return void
     */
    public function setIndexData(Request $request)
    {
        // Запрос в виде массива
        $requestArray = $request->toArray();

        // * Фильтры
        // Дата заезда
        if (!empty($requestArray['start_date'])) {
            $this->backendIndexData['startDate'] = $requestArray['start_date'];
            $this->indexData['startDate'] = $requestArray['start_date'];
        }
        // Дата выезда
        if (!empty($requestArray['end_date'])) {
            $this->backendIndexData['endDate'] = $requestArray['end_date'];
            $this->indexData['endDate'] = $requestArray['end_date'];
        }

        // Фильтрация по диапазону цен от
        if (!empty($requestArray['filterByMinPrice'])) {
            $this->backendIndexData['filterByMinPrice'] = (float)$requestArray['filterByMinPrice'];
            $this->indexData['filterByMinPrice'] = (float)$requestArray['filterByMinPrice'];
        }
        // Фильтрация по диапазону цен до
        if (!empty($requestArray['filterByMaxPrice'])) {
            $this->backendIndexData['filterByMaxPrice'] = (float)$requestArray['filterByMaxPrice'];
            $this->indexData['filterByMaxPrice'] = (float)$requestArray['filterByMaxPrice'];
        }

        // Фильтрация по названию отеля
        if (!empty($requestArray['filterByHotelId'])) {
            $this->backendIndexData['filters']['id'] = [(int)$requestArray['filterByHotelId']];
            $this->indexData['filterByHotelId'] = (int)$requestArray['filterByHotelId'];
        }

        // Фильтрация по удобствам отеля (множественная)
        if (!empty($requestArray['filterByFacilityId'])) {
            // Преобразование строки вида ("2,3,4") в массив
            $arr = convertFilterStringToArrow($requestArray['filterByFacilityId']);
            // Список id выбранных удобств в виде массива, для фронта
            $this->indexData['selectedFacility']['ids'] = $arr;
            // Список id выбранных удобств в виде массива, для бэка
            $this->backendIndexData['filters']['facilityId'] = $arr;
            // Список id выбранных удобств в виде строки, для фронта
            $this->indexData['filterByFacilityId'] = implode(",", $arr);
        }

        // Фильтрация по удобствам номера (множественная)
        if (!empty($requestArray['filterByRoomFacilityId'])) {
            // Преобразование строки вида ("2,3,4") в массив
            $arr = convertFilterStringToArrow($requestArray['filterByRoomFacilityId']);
            // Список id выбранных удобств в виде массива, для фронта
            $this->indexData['selectedRoomFacility']['ids'] = $arr;
            // Список id выбранных удобств в виде массива, для бэка
            $this->backendIndexData['filters']['facilityRoomFacilityId'] = $arr;
            // Список id выбранных удобств в виде строки, для фронта
            $this->indexData['filterByRoomFacilityId'] = implode(",", $arr);
        }

        // * Сортировка
        if (isset($requestArray['sort'])) {
            // Если такая сортировка разрешена
            if (isset(self::ALLOWED_SORT_TYPES[$requestArray['sort']])) {
                $this->backendIndexData['sort'] = self::ALLOWED_SORT_TYPES[$requestArray['sort']];
                $this->indexData['sort'] = $requestArray['sort'];
            }
        }
    }
}
