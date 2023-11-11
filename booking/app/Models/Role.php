<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    // Название таблицы "Роли"
    protected $table = 'roles';
    // Переназначение первичного ключа (вместо стандартного id)
    protected $primaryKey = 'name';
    // Первичный ключ не является автоинкрементным
    public $incrementing = false;
    // Поля, запрещённые для записи и редактирования при массовом заполнении
    protected $guarded = false;
}
