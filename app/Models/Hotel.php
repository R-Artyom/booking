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
    // Постоянная жадная загрузка
    protected $with = ['facilities'];

    // Удобства
    public function facilities()
    {
        // Связь с таблицей facilities - многие ко многим (через сводную таблицу), автозапись меток времени
        return $this->belongsToMany(Facility::class, 'facility_hotel', 'hotel_id','facility_id')->withTimestamps();
    }

    // Номера, которые есть у отеля
    public function rooms()
    {
        // Связь с таблицей rooms - один ко многим
        return $this->hasMany(Room::class, 'hotel_id', 'id')->with(['bookings', 'facilities']);
    }

    // Отзывы, которые есть у отеля
    public function feedbacks()
    {
        // Связь с таблицей feedbacks - один ко многим
        return $this->hasMany(Feedback::class, 'hotel_id', 'id');
    }

    /**
     * Аксессор - Преобразование атрибута poster_url (URL изображения отеля) при запросе
     *
     * @param string $value Оригинальное значение атрибута poster_url
     * @return string - Преобразованное значение атрибута poster_url
     */
    public function getPosterUrlAttribute(string $value): string
    {
        // Если ссылка готовая (изображение хранится в сетке) - то изменять не надо
        if (substr($value, 0, 4) === 'http') {
            return $value;
        // Если ссылка не начинается на http (изображение хранится локально в папке public) - то надо сформировать ссылку
        } else {
            // Ссылка вида http://.../storage/hotels/JcxrrCpweNqU1LRv7UmNeNGEk4ejDtGgsosNJNRD.jpg
            return asset('storage/' . $value);
        }
    }
}
