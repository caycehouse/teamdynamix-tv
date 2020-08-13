<?php

namespace App\Console;

use App\Jobs\GetDeviceStatus;
use App\Jobs\GetNewTickets;
use App\Jobs\GetPapercutStatuses;
use App\Jobs\GetPrinterStatus;
use App\Jobs\GetResolutions;
use App\Jobs\UpdateTickets;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new GetNewTickets())->everyMinute();
        $schedule->job(new UpdateTickets())->everyMinute();
        $schedule->job(new GetResolutions())->everyMinute();
        $schedule->job(new GetDeviceStatus())->everyMinute();
        $schedule->job(new GetPapercutStatuses())->everyMinute();
        $schedule->job(new GetPrinterStatus())->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
