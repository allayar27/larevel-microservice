<?php

namespace App\Jobs;

use App\Services\RabbitMQService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;


class OrderCreated implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    public function __construct(public array $data)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(RabbitMQService $service): void
    {
        $message = json_encode($this->data);

        $service->publish($message, 'order.events', 'order.created');

        // Queue::connection('rabbitmq')->pushRaw(
        //     json_encode([
        //         'event' => 'order.created',
        //         'data' => $this->order,
        //     ]),
        //     'order.created'
        // );
    }
}
