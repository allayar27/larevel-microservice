<?php

namespace App\Console\Commands;

use App\Enums\ProductEvent;
use App\Services\ProductEventService;
use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeProductEvents extends Command
{
    protected $signature = 'consume:product-events';

    protected $description = 'Command description';

    public function handle(ProductEventService $service)
    {
        $connection = $service->setConnection();

        $channel = $connection->channel();

        $this->declareBindings($channel);

        $channel->basic_consume(
            'order-service.products',
            '',
            false,
            false,
            false,
            false,
            fn (AMQPMessage $msg) => $service->dispatch($msg)
        );

        while($channel->is_consuming()) {
            $channel->wait();
        }
    }

    private function declareBindings($channel)
    {
        $channel->exchange_declare(
            'product.events',
            'topic',
            false,
            true,
            false
        );

        $channel->queue_declare(
            'order-service.products',
            false,
            true,
            false,
            false
        );

        $channel->queue_bind('order-service.products', 'product.events', ProductEvent::CREATED->value);
        $channel->queue_bind('order-service.products', 'product.events', ProductEvent::UPDATED->value);
        $channel->queue_bind('order-service.products', 'product.events', ProductEvent::DELETED->value);
    }
}

