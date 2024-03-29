<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Напоминание о бронировании за 3 дня до заезда (Каждый день в 10:00)
         $schedule->command('bookings-reminders:run')->dailyAt('10:00');
        // Обновление статусов бронирований (Каждый день в 00:00)
        $schedule->command('bookings-statuses:update')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
