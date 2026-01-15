<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\ProductEventService;

class OrderObserver
{
    public function __construct(protected ProductEventService $service)
    {

    }

    public function created(Order $order): void
    {
        $order->refresh();

        $this->service->publish(
            'order.created',
            [
                'product_id'    => $order->product_id,
                'count' => $order->count
            ],
        );
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
