<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Ticket;

class StatsChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * The JSON data to return.
     * 
     * @return JSON
     */
    public function broadcastWith()
    {
        $fromDate = Carbon::now()->startOfWeek()->toDateTimeString();
        $tillDate = Carbon::now()->toDateTimeString();

        $stats = Ticket::resolved()->whereBetween('ticket_created_at', [$fromDate, $tillDate])
            ->groupBy('resolved_by')->select('resolved_by', DB::raw('count(*) as total'))->orderBy('total', 'desc')->get();

        return [
            'stat' => $stats
        ];
    }

    /**
     * The event to broadcast as.
     * 
     * @return String
     */
    public function broadcastAs()
    {
        return 'StatsChanged';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('stats');
    }
}
