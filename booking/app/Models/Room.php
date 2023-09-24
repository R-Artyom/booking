<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    // Явное название таблицы
    protected $table = 'rooms';
    // Снять защиту массового заполнения модели
    protected $guarded = false;

    // Отель
    public function hotel()
    {
        // Связь номеров с отелями - принадлежит одному (обратная связь "Один ко многим")
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    // Удобства
    public function facilities()
    {
        // Связь номеров с удобствами - многие ко многим (через сводную таблицу)
        return $this->belongsToMany(Facility::class, 'facility_room', 'room_id','facility_id');
    }

    // Бронирования
    public function bookings()
    {
        // Связь номера с бронированиями - один ко многим
        return $this->hasMany(Booking::class, 'room_id', 'id');
    }
}
