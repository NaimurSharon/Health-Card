<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $doctorId;

    public function __construct($callId, $doctorId)
    {
        $this->callId = $callId;
        $this->doctorId = $doctorId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('doctor.' . $this->doctorId . '.calls');
    }

    public function broadcastAs()
    {
        return 'VideoCallCancelled';
    }

    public function broadcastWith()
    {
        return [
            'callId' => $this->callId
        ];
    }
}