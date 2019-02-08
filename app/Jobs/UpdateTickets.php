<?php

namespace App\Jobs;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

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
        $tickets = Ticket::unresolved()->get();

        foreach ($tickets as $t) {
            Redis::throttle('tdupdate')->allow(59)->every(60)->then(function () use ($t) {
                // Fetch new information on ticket.
                $t->fetch();
            }, function () {
                // Could not obtain lock...
                return $this->release(10);
            });
        }
    }
}
