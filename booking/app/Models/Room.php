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

    /**
     * Аксессор - Преобразование атрибута poster_url (URL изображения номера отеля) при запросе
     *
     * @param string $value Оригинальное значение атрибута poster_url
     * @return string Преобразованное значение атрибута poster_url
     */
    public function getPosterUrlAttribute(string $value): string
    {
        // Если ссылка готовая (изображение хранится в интерсетке) - то изменять не надо
        if (substr($value, 0, 4) === 'http') {
            return $value;
        // Если ссылка не начинается на http (изображение хранится локально в папке public) - то надо сформировать ссылку
        } else {
            // Ссылка вида http://.../storage/rooms/JcxrrCpweNqU1LRv7UmNeNGEk4ejDtGgsosNJNRD.jpg
            return asset('storage/' . $value);
        }
    }
}
