<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Notifaction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;

    public $name;

    public $friend;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$name,$friend)
    {
        $this->id = $id;
        $this->name = $name;
        $this->friend = $friend;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['not_privete'];
    }
}
