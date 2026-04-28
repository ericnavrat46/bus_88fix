<?php

namespace App\Events;

use App\Models\FlashSale;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FlashSaleCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $flashSale;

    /**
     * Create a new event instance.
     */
    public function __construct(FlashSale $flashSale)
    {
        $this->flashSale = $flashSale;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('promos'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'flash-sale.created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'title' => $this->flashSale->title,
            'message' => 'Promo baru telah hadir: ' . $this->flashSale->title . '!',
            'discount' => $this->flashSale->discount_value,
            'type' => $this->flashSale->discount_type,
            'url' => route('home'), // Or a specific promo page if it exists
        ];
    }
}
