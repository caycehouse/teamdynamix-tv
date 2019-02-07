<?php

namespace App\Console\Commands;

use App\Printer;
use Illuminate\Console\Command;

class GetPrinterStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teamdynamix:getprinterstats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets printer stats from PaperCut';

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
        Printer::getStats();
    }
}
