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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Доступно всем аутентифицированным пользователям
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Booking $booking)
    {
        // Владелец бронирования
        $isOwner = $booking->user_id === $user->id;

        // Доступно владельцу бронирования
        if ($isOwner) {
            return true;
        }

        // Остальным недоступно
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Доступно всем аутентифицированным пользователям
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Booking $booking)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Booking $booking)
    {
        // Отмена бронирования - доступно владельцу, админу и менеджеру отеля
        return $this->view($user, $booking) || $this->viewAdmin($user, $booking);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Booking $booking)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Booking $booking)
    {
        //
    }

    /**
     * Управление бронированиями
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAnyAdmin(User $user)
    {
        // Админ
        $isAdmin = $user->roles->containsStrict('name', 'admin');
        // Менеджер
        $isManager = $user->roles->containsStrict('name', 'manager');

        // Доступно админу и менеджеру
        if ($isAdmin || $isManager) {
            return true;
        }

        // Остальным недоступно
        return false;
    }

    /**
     * Управление бронированием
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAdmin(User $user, Booking $booking)
    {
        // Админ
        $isAdmin = $user->roles->containsStrict('name', 'admin');
        // Менеджер отеля
        $isManager = $user->roles->containsStrict('name', 'manager') && $user->hotels->containsStrict('id', $booking->room->hotel_id);

        // Доступно админу и менеджеру отеля
        if ($isAdmin || $isManager) {
            return true;
        }

        // Остальным недоступно
        return false;
    }
}
