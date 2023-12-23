<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

// ****************************************************************************
//                                 Фильтрация
// ****************************************************************************

    /**
     * Фильтрация коллекции
     *
     * @param Collection $collection фильтруемая коллекция
     * @param array|null $filters массив фильтров из запроса
     * @param array $filtersFormat формат данных
     * @return Collection
     */
    protected function filterCollection(Collection $collection, ?array $filters, array $filtersFormat): Collection
    {
        if (!empty($filters)) {
            // * Фильтрация коллекции по шаблону
            if (isset($filtersFormat['filtersByLike'])) {
                $filtersFormat['filtersByLike'] = array_flip($filtersFormat['filtersByLike']); // Поменять местами ключи с их значениями в массиве
                $filtersByLike = array_intersect_key($filters, $filtersFormat['filtersByLike']); // Извлечь необходимые поля фильтра
                if (!empty($filtersByLike)) {
                    $this->filterCollectionByLike($collection, $filtersByLike);
                }
            }

            // * Фильтрация коллекции по значению
            if (isset($filtersFormat['filtersByIn'])) {
                $filtersFormat['filtersByIn'] = array_flip($filtersFormat['filtersByIn']); // Поменять местами ключи с их значениями в массиве
                $filtersByIn = array_intersect_key($filters, $filtersFormat['filtersByIn']); // Извлечь необходимые поля фильтра
                if (!empty($filtersByIn)) {
                    $this->filterCollectionByIn($collection, $filtersByIn);
                }
            }
        }

        // Отфильтрованная коллекция
        return $collection;
    }

    /**
     * Запрос опций фильтрации по полям
     *
     * @param Collection $collection фильтруемая коллекция
     * @param array|null $filters массив фильтров из запроса
     * @param array $filtersFormat формат данных
     * @return array
     */
    protected function getOptionsForFilters(Collection $collection, ?array $filters, array $filtersFormat): array
    {
        // * Опции фильтрации по полям:
        // Начальные значения
        $filterLists = [];
        if (isset($filtersFormat['filtersByIn'])) {
            // Поменять местами ключи с их значениями в массиве
            $filtersFormat['filtersByIn'] = array_flip($filtersFormat['filtersByIn']);
            // * Корректировка фильтра, если он есть в запросе (извлечь только ожидаемые поля)
            if (!empty($filters)) {
                $filters = array_intersect_key($filters, $filtersFormat['filtersByIn']);
            }
            // * Определение полей-массивов элементов коллекции
            $isArrayByFields = $this->getIsArrayByFields($collection);

            // Для каждого поля ожидаемого фильтра
            foreach ($filtersFormat['filtersByIn'] as $filtersField => $filtersValue) {
                // Каждый новый цикл нужна исходная коллекция
                $filteredCollect = $collection;
                // Если в запросе присутствуют ожидаемые поля для фильтрации
                if (!empty($filters)) {
                    // Инициализация массива, который необходимо исключить из фильтра
                    $excludeArr = [];
                    $excludeArr[$filtersField] = $filtersValue;
                    // Убрать из принятого массива текущее поле
                    $cutValidatedFilters = array_diff_key($filters, $excludeArr);
                    // Если после удаления поля массив непустой
                    if (!empty($cutValidatedFilters)) {
                        // * Фильтрация коллекции
                        $this->filterCollectionByIn($filteredCollect, $cutValidatedFilters);
                    }
                }

                // Если коллекция пустая
                if ($filteredCollect->isEmpty()) {
                    $filterLists[$filtersField] = [];
                // Если выходное поле является массивом
                } elseif ($isArrayByFields[$filtersField]) {
                    // Если у элемента коллекции этот массив пустой, то в него необходимо добавить одно значение - null, для дальнейшего поиска на фронте по пустым значениям
                    $filteredCollect = $filteredCollect->map(function ($item) use ($filtersField) {
                        // Два варианта коллекции - массивами и с объектами
                        if (is_array($item)) {
                            $item[$filtersField] = empty($item[$filtersField]) ? [null] : $item[$filtersField];
                        } else {
                            $item->$filtersField = empty($item->$filtersField) ? [null] : $item->$filtersField;
                        }
                        return $item;
                    });
                    // Итоговые опции фильтрации для поля $field (собранные в один массив, уникальные, без сохранения оригинальных ключей)
                    // Равнозначно, но быстрее, чем $filterLists[$filtersField] = $filteredCollect->pluck($filtersField)->collapse()->unique()->values()->toArray();
                    foreach ($filteredCollect as $item) {
                        $itemFiltersField = is_array($item) ? $item[$filtersField] : $item->$filtersField;
                        foreach ($itemFiltersField as $value) {
                            $filterLists[$filtersField][] = $value;
                        }
                    }
                    $filterLists[$filtersField] = array_values(array_unique($filterLists[$filtersField]));
                } else {
                    // Итоговые опции фильтрации для поля $field (уникальные, без сохранения оригинальных ключей)
                    // Равнозначно, но быстрее, чем  $filterLists[$filtersField] = $filteredCollect->pluck($filtersField)->unique()->values()->toArray();
                    foreach ($filteredCollect as $item) {
                        $filterLists[$filtersField][] = is_array($item) ? $item[$filtersField] : $item->$filtersField;
                    }
                    $filterLists[$filtersField] = array_values(array_unique($filterLists[$filtersField]));
                }
            }
        }
        // Опции фильтрации
        return $filterLists;
    }

    /**
     * Фильтрация коллекции по значению (низкий уровень)
     *
     * @param Collection $collection фильтруемая коллекция
     * @param array $filters массив фильтров из запроса
     * @return void
     */
    private function filterCollectionByIn(Collection &$collection, array $filters)
    {
        if (!$collection->isEmpty()) {
            // * Определение полей-массивов элементов коллекции
            $isArrayByFields = $this->getIsArrayByFields($collection);
            // * Фильтрация по всем полям фильтра
            foreach ($filters as $field => $value) {
                // Если выходное поле является массивом
                if ($isArrayByFields[$field]) {
                    // Фильтрация коллекции при помощи функции
                    $collection = $collection->filter(function ($item) use ($field, $value) {
                        // По каждому значению входного фильтрующего поля
                        foreach ($value as $inputFilterElement) {
                            // Массив выходного поля для двух вариантов коллекции (с массивами и с объектами)
                            $outputArray = is_array($item) ? $item[$field] : $item->$field;
                            // Если выходной массив пустой, то в него необходимо добавить одно значение - null, для поиска на фронте по пустым значениям
                            $outputArray = empty($outputArray) ? [null] : $outputArray;
                            // Если в массиве выходного поля есть значение фильтра (нестрогое сравнение)
                            if (in_array($inputFilterElement, $outputArray)) {
                                // Значит элемент коллекции надо оставить
                                return true;
                            }
                        }
                        // Все остальные элементы коллекции удалить
                        return false;
                    });
                } else {
                    // Удалить элементы коллекции, у которых значение поля отлично
                    // от указанных в массиве поля входящего фильтра
                    $collection = $collection->whereIn($field, $value);
                }
            }
        }
    }

    /**
     * Фильтрация коллекции по шаблону (низкий уровень)
     *
     * @param Collection $collection фильтруемая коллекция
     * @param array $filters массив фильтров из запроса
     * @return void
     */
    private function filterCollectionByLike(Collection &$collection, array $filters)
    {
        if (!$collection->isEmpty()) {
            // * Определение полей-массивов элементов коллекции
            $isArrayByFields = $this->getIsArrayByFields($collection);
            // * Фильтрация по всем полям фильтра
            foreach ($filters as $field => $value) {
                // Если выходное поле является массивом
                if ($isArrayByFields[$field]) {
                    // Фильтрация коллекции при помощи функции
                    $collection = $collection->filter(function ($item) use ($field, $value) {
                        // Шаблон
                        $pattern = '/' . $value . '/iu';
                        // Массив выходного поля для двух вариантов коллекции (с массивами и с объектами)
                        $outputArray = is_array($item) ? $item[$field] : $item->$field;
                        // По каждому значению массива вЫходного поля
                        foreach ($outputArray as $outputArrayValue) {
                            // Если шаблон входит в значение поля
                            if (preg_match($pattern, $outputArrayValue) === 1) {
                                return true;
                            }
                        }
                        // Все остальные элементы коллекции удалить
                        return false;
                    });
                } else {
                    // Удалить элементы коллекции, у которых указанный шаблон не входит в значение поля
                    $collection = $collection->filter(function ($item) use ($field, $value) {
                        // Шаблон
                        $pattern = '/' . $value . '/iu';
                        return preg_match($pattern, $item[$field]) === 1;
                    });
                }
            }
        }
    }

    /**
     * Определение полей-массивов элементов коллекции
     *
     * @param Collection $collection проверяемая коллекция
     * @return array массив вида ["Поле1" => true, "Поле2" => false] (1е - массив, 2е - нет)
     */
    private function getIsArrayByFields(Collection &$collection): array
    {
        $isArrayByFields = [];
        if (!$collection->isEmpty()) {
            foreach ($collection->first() as $field => $value) {
                $isArrayByFields[$field] = is_array($value);
            }
        }
        return $isArrayByFields;
    }

// ****************************************************************************
//                                Словари
// ****************************************************************************

    /**
     * Получение словарей для конечного результата табличного метода
     *
     * @param Collection|array $data отфильтрованная и срезанная коллекция (или массив)
     * @param array $filterOptions опции фильтрации полей
     * @param array $dictionariesAll полный словарь, из которого необходимо брать значения
     * @param array $dictionariesFormat формат словарей
     * @return array
     */
    protected function getResultDictionaries($data, array $filterOptions, array $dictionariesAll, array $dictionariesFormat): array
    {
        $dictionaries = [];
        if (!empty($dictionariesFormat)) {
            // Если для опций не нужен словарь
            if (empty($filterOptions)) {
                $dictionaries = $this->getDataObjectsDictionaries($data, $dictionariesAll, $dictionariesFormat);
            } else {
                // * Определение формата словарей для данных с объектами
                // Получение всех полей опций фильтрации
                $filterOptionsFields = [];
                foreach ($filterOptions as $field => $value) {
                    $filterOptionsFields[] = $field;
                }
                // Расхождение массивов - нужны словари только на те поля, которых нет в опциях фильтрации
                $dataDictionariesFormat = array_diff_key($dictionariesFormat, array_flip($filterOptionsFields));

                // * Получить словари для опций фильтрации
                $dictionaries = $this->getFilterOptionsDictionaries($filterOptions, $dictionariesAll, $dictionariesFormat);

                // * Добавить словари для данных с объектами
                $dictionaries = array_merge($dictionaries, $this->getDataObjectsDictionaries($data, $dictionariesAll, $dataDictionariesFormat));
            }
        }
        return $dictionaries;
    }

    /**
     * Получение словарей для всех опций фильтрации
     *
     * @param array $filterOptions опции фильтрации полей
     * @param array $dictionariesAll полный словарь, из которого необходимо брать значения
     * @param array $dictionariesFormat формат словарей
     * @return array
     */
    protected function getFilterOptionsDictionaries(array $filterOptions, array $dictionariesAll, array $dictionariesFormat): array
    {
        $dictionaries = [];
        if (!empty($dictionariesFormat)) {
            foreach ($filterOptions as $field => $options) {
                if (!empty($options)) {
                    // Если нужен словарь для такого поля
                    if (array_key_exists($field, $dictionariesFormat)) {
                        // Название словаря
                        $dictionaryName = $dictionariesFormat[$field];
                        // Перебор всех опций поля
                        foreach ($options as $option) {
                            // Отображать только непустое значение, иначе ключ = null или ""
                            if (isset($option)) {
                                $dictionaries[$dictionaryName][$option] = $dictionariesAll[$dictionaryName][$option];
                            }
                        }
                        // Сортировка по значению по возрастанию
                        if(isset($dictionaries[$dictionaryName])) {
                            asort($dictionaries[$dictionaryName]);
                        }
                    }
                }
            }
        }
        return $dictionaries;
    }

    /**
     * Получение словарей для объектов поля "data" отфильтрованной и срезанной коллекции
     *
     * @param Collection|array $data отфильтрованная и срезанная коллекция / массив
     * @param array $dictionariesAll полный словарь, из которого необходимо брать значения
     * @param array $dictionariesFormat формат словарей
     * @return array
     */
    protected function getDataObjectsDictionaries($data, array $dictionariesAll, array $dictionariesFormat): array
    {
        $dictionaries = [];
        if (!empty($dictionariesFormat)) {
            // Для отфильтрованной и срезанной коллекции:
            foreach ($data as $item) {
                // Пробежка по полям объекта
                foreach ($item as $field => $value) {
                    // Если нужен словарь для поля
                    if (isset($dictionariesFormat[$field])) {
                        // Название словаря
                        $dictionaryName = $dictionariesFormat[$field];
                        // Если значение поля - массив
                        if (is_array($value)) {
                            foreach ($value as $arrayValue) {
                                if (isset($arrayValue)) {
                                    // Отображать только непустое значение, иначе ключ = null или ""
                                    $dictionaries[$dictionaryName][$arrayValue] = $dictionariesAll[$dictionaryName][$arrayValue];
                                }
                            }
                            // Если значение поля - НЕ массив
                        } else {
                            // Отображать только непустое значение, иначе ключ = null или ""
                            if (isset($value)) {
                                $dictionaries[$dictionaryName][$value] = $dictionariesAll[$dictionaryName][$value];
                            }
                        }
                    }
                }
            }
        }
        return $dictionaries;
    }
}
