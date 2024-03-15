<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelUser extends Model
{
    use HasFactory;

    // Название таблицы "Отели менеджеров"
    protected $table = 'hotel_user';
    // Поля, запрещённые для записи и редактирования при массовом заполнении
    protected $guarded = ['id'];
}
