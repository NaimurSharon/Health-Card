<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantJoined implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $participant;

    public function __construct($callId, $participant)
    {
        $this->callId = $callId;
        $this->participant = $participant;
    }

    public function broadcastOn()
    {
        return new Channel('video-call.' . $this->callId);
    }

    public function broadcastAs()
    {
        return 'ParticipantJoined';
    }

    public function broadcastWith()
    {
        return [
            'participant' => $this->participant,
            'callId' => $this->callId
        ];
    }
}
