<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    // Явное название таблицы
    protected $table = 'hotel';
    // Снять защиту массового заполнения модели
    protected $guarded = false;

    // Удобства
    public function facilities()
    {
        // Связь с таблицей facilities - многие ко многим (через сводную таблицу)
        return $this->belongsToMany(Facility::class, 'facility_hotel', 'hotel_id','facility_id');
    }
}
