<?php

namespace App\Console\Commands;

use App\Http\Controllers\Bookings\RemindersRunController;
use Illuminate\Console\Command;

class BookingsRemindersRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings-reminders:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск отправки уведомлений с напоминаниями о бронированиях';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Отправка уведомлений с напоминаниями по расписанию
        (new RemindersRunController)();
    }
}
