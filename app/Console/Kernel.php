<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\GetNewTickets;
use App\Jobs\RemoveClosedTickets;
use App\Jobs\GetPrinterStatus;
use App\Jobs\RemoveFixedPrinters;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new GetNewTickets)->everyFiveMinutes();
        $schedule->job(new RemoveClosedTickets)->everyFifteenMinutes();
        $schedule->job(new GetPrinterStatus)->everyFiveMinutes();
        $schedule->job(new RemoveFixedPrinters)->everyFiveMinutes();
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
