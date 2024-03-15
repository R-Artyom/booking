<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    // Разрешенная сортировка
    const ALLOWED_SORT_TYPES = [
        // По номеру бронирования
        'id' => [               // Поле из запроса фронта
            'field' => 'id',    // Поле для формирования сортировки в запросе к БД
            'order' => 'asc',   // Направление сортировки
        ],
        'idDesc' => [
            'field' => 'id',
            'order' => 'desc',
        ],
        // По дата заезда
        'startedAt' => [
            'field' => 'started_at',
            'order' => 'asc',
        ],
        'startedAtDesc' => [
            'field' => 'started_at',
            'order' => 'desc',
        ],
    ];

    // Данные для фронта по умолчанию
    public array $indexData = [
        // Фильтры
        'filters' => [
            // Id отеля
            'filterByHotel' => 0,
            // Id пользователя
            'filterByUser' => 0,

        ],
        // Опции фильтрации
        'filterOptions' => [],
        // Сортировка по возрастанию даты заезда
        'sort' => 'startedAt',
        // Завершенные не показывать
        'showOld' => 'no',
    ];

    // Данные для бэка по умолчанию
    public array $backendIndexData = [
        // Фильтры
        'filters' => [],
        // Параметры сортировки
        'sort' => [
            'field' => 'id',    // Поле для формирования сортировки в запросе к БД
            'order' => 'asc',   // Направление сортировки
        ],
        // Завершенные не показывать
        'showOld' => false,
    ];

    /**
     * Формирование данных для шаблона страницы "Список бронирований"
     * @param Request $request
     * @return void
     */
    public function setIndexData(Request $request)
    {
        // Запрос в виде массива
        $requestArray = $request->toArray();

        // * Фильтры
        // Фильтрация по завершённым
        if (isset($requestArray['showOld'])) {
            // Если такая фильтрация разрешена
            if ($requestArray['showOld'] === 'yes') {
                $this->backendIndexData['showOld'] = true;
                $this->indexData['showOld'] = $requestArray['showOld'];
            }
        }

        // Фильтрация по отелям
        if (!empty($requestArray['filterByHotel'])) {
            // Если такая фильтрация разрешена
            $this->backendIndexData['filters']['hotelId'] = [(int)$requestArray['filterByHotel']];
            $this->indexData['filters']['filterByHotel'] = (int)$requestArray['filterByHotel'];
        }

        // Фильтрация по пользователям
        if (!empty($requestArray['filterByUser'])) {
            // Если такая фильтрация разрешена
            $this->backendIndexData['filters']['userId'] = [(int)$requestArray['filterByUser']];
            $this->indexData['filters']['filterByUser'] = (int)$requestArray['filterByUser'];
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
