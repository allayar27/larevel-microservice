<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class OrderCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $productId, public int $count)
    {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            productId: $payload["product_id"],
            count: $payload["count"],
        );
    }
}
