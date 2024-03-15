<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityRoom extends Model
{
    use HasFactory;
    // Явное название таблицы
    protected $table = 'facility_room';
    // Снять защиту массового заполнения модели
    protected $guarded = false;
}
