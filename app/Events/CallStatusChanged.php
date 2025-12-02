<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $consultationId;
    public $status;
    public $endedBy;

    /**
     * Create a new event instance.
     */
    public function __construct($consultationId, $status, $endedBy)
    {
        $this->consultationId = $consultationId;
        $this->status = $status; // 'ended', 'rejected', etc.
        $this->endedBy = $endedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('consultation.' . $this->consultationId);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'call.status.changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'consultation_id' => $this->consultationId,
            'status' => $this->status,
            'ended_by' => $this->endedBy,
            'timestamp' => now()->toISOString()
        ];
    }
}
