<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Постоянная жадная загрузка
    protected $with = ['roles'];

    // Брони пользователя
    public function bookings()
    {
        // Связь с таблицей bookings - один ко многим
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    // Роли пользователя
    public function roles()
    {
        // Связь с таблицей roles - многие ко многим (через сводную таблицу), автозапись меток времени
        return $this->belongsToMany(Role::class, 'role_user', 'user_id','name')->withTimestamps();
    }

    // Отели пользователя
    public function hotels()
    {
        // Связь с таблицей hotels - многие ко многим (через сводную таблицу), автозапись меток времени
        return $this->belongsToMany(Hotel::class, 'hotel_user', 'user_id','hotel_id')->withTimestamps();
    }
}
