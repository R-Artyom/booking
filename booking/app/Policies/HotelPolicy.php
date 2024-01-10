<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class HotelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // * Просмотр списка отелей
        // Если это панель администратора - доступно админу и любому менеджеру
        if (request()->route()->getPrefix() === '/admin') {
            return $this->isAdmin($user) || $this->isManager($user);
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
    public function view(User $user, Hotel $hotel)
    {
        // * Просмотр отеля
        // Если это панель администратора - доступно админу и менеджеру отеля
        if (request()->route()->getPrefix() === '/admin') {
            return $this->isAdmin($user) || $this->isHotelManager($user, $hotel);
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
    public function create(User $user)
    {
        // * Создание отеля - доступно админу и любому менеджеру
        return $this->isAdmin($user) || $this->isManager($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Hotel $hotel
     * @return bool
     */
    public function update(User $user, Hotel $hotel)
    {
        // * Редактирование отеля - доступно админу и менеджеру отеля
        return $this->isAdmin($user) || $this->isHotelManager($user, $hotel);
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function isAdmin(User $user): bool
    {
        // Администратор?
        return $user->roles->containsStrict('name', 'admin');
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function isManager(User $user): bool
    {
        // Менеджер?
        return $user->roles->containsStrict('name', 'manager');
    }

    /**
     * @param User $user
     * @param Hotel $hotel
     * @return bool
     */
    protected function isHotelManager(User $user, Hotel $hotel): bool
    {
        // Менеджер отеля?
        return $user->roles->containsStrict('name', 'manager') && $user->hotels->containsStrict('id', $hotel->id);
    }
}
