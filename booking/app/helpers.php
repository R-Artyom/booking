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
