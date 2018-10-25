<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastingModelEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $model;
    protected $eventType;

    /**
     * Construct our Model.
     */
    public function __construct(Model $model, $eventType)
    {
        $this->model = $model;
        $this->eventType = $eventType;
    }


    /**
     * The event to broadcast as.
     *
     * @return String
     */
    public function broadcastAs()
    {
        return get_class($this->model);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('BroadcastingModelEvent');
    }

    /**
     * The JSON data to return.
     *
     * @return JSON
     */
    public function broadcastWith()
    {
        return [
            'model' => $this->model,
            'eventType' => $this->eventType
        ];
    }
}
