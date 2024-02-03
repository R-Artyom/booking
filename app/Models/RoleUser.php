<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;
    // Название таблицы "Роли пользователей"
    protected $table = 'role_user';
    // Поля, запрещённые для записи и редактирования при массовом заполнении
    protected $guarded = ['id'];
}
