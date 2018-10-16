<?php

namespace App\Events;

use App\Printer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PrintersChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        \Log::info('[Event] Printers Changed');
    }

    /**
     * The JSON data to return.
     *
     * @return JSON
     */
    public function broadcastWith()
    {
        return [
            'printer' => Printer::inError()->get()
        ];
    }

    /**
     * The event to broadcast as.
     *
     * @return String
     */
    public function broadcastAs()
    {
        return 'PrintersChanged';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('printers');
    }
}
