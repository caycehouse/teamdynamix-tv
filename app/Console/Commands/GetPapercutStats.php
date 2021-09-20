<?php

namespace App\Console\Commands;

use App\Models\PapercutStatuses;
use Illuminate\Console\Command;

class GetPapercutStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'labtechs:getpapercutstats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets papercut stats from PaperCut';

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
        PapercutStatuses::getStats();
    }
}
