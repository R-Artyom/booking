<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RoomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param int $hotelId
     * @return bool
     */
    public function create(User $user, Hotel $hotel): bool
    {
        // * Создание номера отеля - доступно админу и менеджеру отеля
        return isAdmin($user) || isHotelManager($user, $hotel->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Room $room
     * @return bool
     */
    public function update(User $user, Room $room): bool
    {
        // * Редактирование номера отеля - доступно админу и менеджеру отеля
        return isAdmin($user) || isHotelManager($user, $room->hotel_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Room $room
     * @return Response|bool
     */
    public function delete(User $user, Room $room)
    {
        // * Удаление номера отеля - доступно админу и менеджеру отеля
        return isAdmin($user) || isHotelManager($user, $room->hotel_id);
    }
}
