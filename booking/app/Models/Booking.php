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

    // Пользователь, сделавший бронирование
    public function user()
    {
        // Связь с таблицей users - многие к одному
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
