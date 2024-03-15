<?php

namespace App\Console\Commands;

use App\Http\Controllers\Bookings\StatusesUpdateController;
use Illuminate\Console\Command;

class BookingsStatusesUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings-statuses:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновить статусы бронирований';

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
        // Обновить статусы бронирований
        (new StatusesUpdateController)();
    }
}
