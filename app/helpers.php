<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

// ****************************************************************************
//                         Проверка прав пользователя
// ****************************************************************************

/**
 * @param User|Authenticatable $user
 * @return bool
 */
function isAdmin(User $user): bool
{
    // Администратор?
    return $user->roles->containsStrict('name', 'admin');
}

/**
 * @param User|Authenticatable $user
 * @return bool
 */
function isManager(User $user): bool
{
    // Менеджер?
    return $user->roles->containsStrict('name', 'manager');
}

/**
 * @param User|Authenticatable $user
 * @param int $hotelId
 * @return bool
 */
function isHotelManager(User $user, int $hotelId): bool
{
    // Менеджер отеля?
    return $user->roles->containsStrict('name', 'manager') && $user->hotels->containsStrict('id', $hotelId);
}

/**
 * @param User|Authenticatable $user
 * @return bool
 */
function isGuest(User $user): bool
{
    // Гость?
    return $user->roles->containsStrict('name', 'guest');
}

// ****************************************************************************
//                Проверка текущего роута на панель Администратора
// ****************************************************************************
/**
 * @return bool
 */
function isAdminPanel(): bool
{
    return request()->route()->getPrefix() === '/admin';
}

// ****************************************************************************

/**
 * Преобразование строки фильтра в массив, для организации множественного селекта
 *
 * @param string $filter
 * @return array
 */
function convertFilterStringToArrow(string $filter): array
{
    // Преобразование строки вида ("2,3,4") в массив
    $arr = explode(",", $filter);
    // Массив с id, который необходимо удалить из фильтра (повторный клик)
    $needDelete = array_diff_key($arr, array_unique($arr));
    // Удаление id из списка
    $arr = array_diff($arr, $needDelete);
    // Приведение типов
    return array_map(function($value) {
        return intval($value);
    }, $arr);
}

/**
 * Функция формирования окончания слова "ночь(-и,-ей)", используемое совместно с количеством (напр. "145 ночей")
 *
 * @param int $number количество
 * @return string результирующая фраза
 */
function getPhraseNight(int $number): string
{
    // Остатка от деления на 100 хватит, чтобы создать правильное окончание слова,
    // но остатки 11,12,13,14,15,16,17,18,19 выбиваются из общей картины,
    // поэтому для чисел с остатком 0..19 необходимо проверять остаток от деления на 100,
    // а для всех остальных - остаток от деления на 10.
    if ($number % 100 < 20) {
        $balance = $number % 100;
    } else {
        $balance = $number % 10;
    }

    // * Результат:
    // 1,21,31,41,51,61,71,81,91
    if ($balance === 1) {
        return "ночь";
    // 2,22,32,42,52,62,72,82,92
    // 3,23,33,43,53,63,73,83,93
    // 4,24,34,44,54,64,74,84,94
    } elseif ($balance === 2 || $balance === 3 || $balance === 4) {
        return "ночи";
    // 11,12,13,14,
    // 5,15,25,35,45,55,65,75,85,95
    // 6,16,26,36,46,56,66,76,86,96
    // 7,17,27,37,47,57,67,77,87,97
    // 8,18,28,38,48,58,68,78,88,98
    // 9,19,29,39,49,59,69,79,89,99
    // 0,10,20,30,40,50,60,70,80,90
    } else {
        return "ночей";
    }
}
