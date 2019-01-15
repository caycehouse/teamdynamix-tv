<?php

namespace App\Jobs;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get all unresolved tickets.
        $tickets = Ticket::unresolved()->withTrashed()->get();

        foreach ($tickets as $t) {
            // Fetch new information on ticket.
            $t->fetch();
        }
    }
}
