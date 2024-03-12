<?php

namespace App\Models;

use App\Http\Controllers\Hotels\HelperController as HotelsHelperController;
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

    // Расширение метода boot модели
    protected static function boot()
    {
        // Родительский метод
        parent::boot();
        // * Регистрация действий к событиям:
        // Действие на событие "Сохранено" модели "Отзыв" (после обновления или создания)
        static::saved(function (Feedback $feedback) {
            // Если атрибут "Активность" модели "Отзыв" был изменен
            if ($feedback->wasChanged('is_active')) {
                // Пересчет рейтинга отеля
                (new HotelsHelperController)->updateRating($feedback->hotel);
            }
        });
        // Действие на событие "Удалено" модели "Отзыв" (после обновления или создания)
        static::deleted(function (Feedback $feedback) {
            // Пересчет рейтинга отеля
            (new HotelsHelperController)->updateRating($feedback->hotel);
        });
    }

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
