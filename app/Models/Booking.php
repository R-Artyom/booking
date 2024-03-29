<?php

namespace App\Models;

use App\Events\BookingCreated;
use App\Events\BookingDeleted;
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
    protected $with = ['room', 'user', 'status'];
    // Соответствие стандартных событий кастомным
    protected $dispatchesEvents = [
        'created' => BookingCreated::class,
        'deleted' => BookingDeleted::class,
    ];

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

    // Статус бронирования
    public function status()
    {
        // Связь бронирования со статусами - принадлежит одному (обратная связь "Один ко многим")
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

}
