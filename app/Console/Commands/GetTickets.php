<?php

namespace App\Console\Commands;

use App\Ticket;
use Illuminate\Console\Command;

class GetTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teamdynamix:gettickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets new tickets from TeamDynamix';

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
        Ticket::getNew();
    }
}
