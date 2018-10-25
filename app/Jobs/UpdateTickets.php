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
        Redis::throttle('update-tickets')->allow(29)->every(59)->then(function () {
            // Get all unresolved tickets.
            $tickets = Ticket::unresolved()->get();

            foreach ($tickets as $t) {
            // Fetch new information on ticket.
                $t->fetch();
            }
        }, function () {
            // Could not obtain lock...
            return $this->release(10);
        });
    }
}
