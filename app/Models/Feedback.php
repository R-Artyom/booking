<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Явное название таблицы
    protected $table = 'feedbacks';
    // Снять защиту массового заполнения модели
    protected $guarded = false;
    // Постоянная жадная загрузка
    protected $with = ['user', 'hotel'];

    // Пользователь, написавший отзыв
    public function user()
    {
        // Связь отзыва с пользователями - принадлежит одному (обратная связь "Один ко многим")
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Отель, к которому написан отзыв
    public function hotel()
    {
        // Связь отзыва с отелями - принадлежит одному (обратная связь "Один ко многим")
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }
}
