<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    // Явное название таблицы
    protected $table = 'facilities';
    // Снять защиту массового заполнения модели
    protected $guarded = false;

    // Отели
    public function hotels()
    {
        // Связь с таблицей hotels - многие ко многим (через сводную таблицу)
        return $this->belongsToMany(Hotel::class, 'facility_hotel', 'facility_id','hotel_id');
    }

    // Номера
    public function rooms()
    {
        // Связь с таблицей rooms - многие ко многим (через сводную таблицу)
        return $this->belongsToMany(Room::class, 'facility_room', 'facility_id','room_id');
    }
}
