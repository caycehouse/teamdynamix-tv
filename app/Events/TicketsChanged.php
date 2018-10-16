<?php

namespace App\Events;

use App\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TicketsChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        \Log::info('[Event] Tickets Changed');
    }

    /**
     * The JSON data to return.
     *
     * @return JSON
     */
    public function broadcastWith()
    {
        return [
            'ticket' => Ticket::unresolved()->studentComputerLabs()->orderBy('ticket_created_at', 'desc')->take(20)->get()
        ];
    }

    /**
     * The event to broadcast as.
     *
     * @return String
     */
    public function broadcastAs()
    {
        return 'TicketsChanged';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tickets');
    }
}
