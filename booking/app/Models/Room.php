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
        // Связь с таблицей hotels - один к одному
        return $this->hasOne(Hotel::class, 'hotel_id', 'id');
    }

    // Удобства
    public function facilities()
    {
        // Связь с таблицей facilities - многие ко многим (через сводную таблицу)
        return $this->belongsToMany(Facility::class, 'facility_room', 'room_id','facility_id');
    }


}
