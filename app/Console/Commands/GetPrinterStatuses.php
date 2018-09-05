<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\GetPrinterStatus;

class GetPrinterStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'labtechs:getprinterstatuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets printer statuses from PaperCut.';

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
     * @return mixed
     */
    public function handle()
    {
        GetPrinterStatus::dispatch();
    }
}
