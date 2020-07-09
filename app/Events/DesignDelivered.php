<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DesignDelivered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client_id;
    public $request_id;
    public $msg;
    public $message_id;

    /**
     * Create a new event instance.
     * @param integer $client_id
     * @param integer $request_id
     * @param integer $message_id
     * @param string $message
     * @return void
     */
    public function __construct($client_id, $request_id, $message_id, $message)
    {
        $this->client_id = $client_id;
        $this->request_id = $request_id;
        $this->msg = $message;
        $this->message_id = $message_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
        return ['client-channel'];
    }
}
