<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    // Явное название таблицы
    protected $table = 'hotels';
    // Снять защиту массового заполнения модели
    protected $guarded = false;

    // Удобства
    public function facilities()
    {
        // Связь с таблицей facilities - многие ко многим (через сводную таблицу)
        return $this->belongsToMany(Facility::class, 'facility_hotel', 'hotel_id','facility_id');
    }

    // Номера, которые есть у отеля
    public function rooms()
    {
        // Связь с таблицей rooms - один ко многим
        return $this->hasMany(Room::class, 'hotel_id', 'id');
    }
}
