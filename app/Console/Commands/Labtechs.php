<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\GetNewTickets;

class Labtechs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'labtechs:gettickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets new tickets from TeamDynamix.';

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
        GetNewTickets::dispatch();
    }
}
