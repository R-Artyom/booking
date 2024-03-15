<?php

namespace App\Policies;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacilityPolicy
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
        // * Просмотр списка удобств - доступно админу и любому менеджеру
        return isAdmin($user) || isManager($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // * Создание удобства - доступно админу и любому менеджеру
        return isAdmin($user) || isManager($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Facility $facility
     * @return bool
     */
    public function update(User $user, Facility $facility)
    {
        // * Редактирование удобства - доступно админу и любому менеджеру
        return isAdmin($user) || isManager($user);
    }
}
