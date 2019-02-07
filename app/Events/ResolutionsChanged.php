<?php

namespace App\Events;

use App\Resolution;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResolutionsChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $period;

    /**
     * Create a new event instance.
     *
     * @param $period
     *
     * @return void
     */
    public function __construct($period)
    {
        $this->period = $period;
    }

    /**
     * The JSON data to return.
     *
     * @return JSON
     */
    public function broadcastWith()
    {
        if ('last_week' == $this->period) {
            $resolutions = Resolution::where('period', '=', 'last_week')->get();
        } else {
            $resolutions = Resolution::where('period', '=', 'this_week')->get();
        }

        return [
            'resolutions' => $resolutions,
        ];
    }

    /**
     * The event to broadcast as.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'ResolutionsChanged\\\\'.$this->period;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('resolutions');
    }
}
