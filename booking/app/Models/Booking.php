<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    // Явное название таблицы
    protected $table = 'bookings';
    // Снять защиту массового заполнения модели
    protected $guarded = false;
    // Постоянная жадная загрузка
    protected $with = ['room'];

    // Пользователь, сделавший бронирование
    public function user()
    {
        // Связь бронирования пользователями - принадлежит одному (обратная связь "Один ко многим")
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Забронированный номер отеля
    public function room()
    {
        // Связь бронирования с номерами - принадлежит одному (обратная связь "Один ко многим")
        return $this->belongsTo(Room::class, 'room_id', 'id')->with(['hotel']);
    }
}
