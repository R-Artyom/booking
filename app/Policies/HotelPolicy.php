<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HotelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // * Просмотр списка отелей
        // Если это панель администратора - доступно админу и любому менеджеру
        if (isAdminPanel()) {
            return isAdmin($user) || isManager($user);
        }
        // Если НЕ панель администратора - доступно всем аутентифицированным пользователям
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Hotel $hotel
     * @return bool
     */
    public function view(User $user, Hotel $hotel): bool
    {
        // * Просмотр отеля
        // Если это панель администратора - доступно админу и менеджеру отеля
        if (isAdminPanel()) {
            return isAdmin($user) || isHotelManager($user, $hotel->id);
        }
        // Если НЕ панель администратора - доступно всем аутентифицированным пользователям
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // * Создание отеля - доступно админу и любому менеджеру
        return isAdmin($user) || isManager($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Hotel $hotel
     * @return bool
     */
    public function update(User $user, Hotel $hotel): bool
    {
        // * Редактирование отеля - доступно админу и менеджеру отеля
        return isAdmin($user) || isHotelManager($user, $hotel->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Hotel $hotel
     * @return bool
     */
    public function delete(User $user, Hotel $hotel): bool
    {
        // Удаление отеля - доступно админу и менеджеру отеля
        return isAdmin($user) || isHotelManager($user, $hotel->id);
    }
}
