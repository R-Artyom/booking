<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
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
        // * Просмотр списка бронирований
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
     * @param Booking $booking
     * @return bool
     */
    public function view(User $user, Booking $booking): bool
    {
        // * Просмотр бронирования
        $isOwner = $booking->user_id === $user->id;
        // Если это панель администратора - доступно владельцу бронирования, админу и менеджеру отеля
        if (isAdminPanel()) {
            return $isOwner || isAdmin($user) || isHotelManager($user, $booking->room->hotel_id);
        }
        // Если НЕ панель администратора - доступно только владельцу бронирования
        return $isOwner;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // * Создание бронирования - доступно всем аутентифицированным пользователям
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function delete(User $user, Booking $booking): bool
    {
        // * Отмена бронирования - доступно владельцу, админу и менеджеру отеля
        return $booking->user_id === $user->id || isAdmin($user) || isHotelManager($user, $booking->room->hotel_id);
    }
}
