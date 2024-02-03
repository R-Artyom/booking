<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityHotel extends Model
{
    use HasFactory;
    // Явное название таблицы
    protected $table = 'facility_hotel';
    // Снять защиту массового заполнения модели
    protected $guarded = false;
}
