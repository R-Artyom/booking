<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use App\Policies\BookingPolicy;
use App\Policies\HotelPolicy;
use App\Policies\RoomPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Политика бронирования
        Booking::class => BookingPolicy::class,
        // Политика отеля
        Hotel::class => HotelPolicy::class,
        // Политика отеля
        Room::class => RoomPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
