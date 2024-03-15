<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
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
        // * Просмотр списка отзывов
        // Если это панель администратора - доступно админу и любому менеджеру
        if (isAdminPanel()) {
            return isAdmin($user) || isManager($user);
        }
        // Если НЕ панель администратора - доступно всем аутентифицированным пользователям
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Hotel $hotel
     * @return bool
     */
    public function create(User $user, Hotel $hotel): bool
    {
        // * Создание отеля - доступно пользователям, которые уже побывали в отеле
        return isHotelGuest($user, $hotel->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Feedback $feedback
     * @return bool
     */
    public function update(User $user, Feedback $feedback): bool
    {
        // * Редактирование отзыва - доступно только автору отзыва
        $isOwner = $feedback->user_id === $user->id;
        return $isOwner;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Feedback $feedback
     * @return bool
     */
    public function delete(User $user, Feedback $feedback): bool
    {
        // * Удаление отзыва - доступно только автору отзыва
        $isOwner = $feedback->user_id === $user->id;
        return $isOwner;
    }
}
