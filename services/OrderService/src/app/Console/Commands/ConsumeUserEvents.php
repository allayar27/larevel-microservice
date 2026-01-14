<?php

namespace App\Console\Commands;

use App\Services\UserEventService;
use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeUserEvents extends Command
{
    protected $signature = 'consume:user-events';

    protected $description = 'Command description';

    public function handle(UserEventService $service)
    {
        $connection = $service->setConnection();

        $channel = $connection->channel();

        $this->declareBindings($channel);

        $channel->basic_consume(
            'order-service.users',
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
            'users.events',
            'topic',
            false,
            true,
            false
        );

        $channel->queue_declare(
            'order-service.users',
            false,
            true,
            false,
            false
        );

        $channel->queue_bind('order-service.users', 'users.events', 'user.created');
        $channel->queue_bind('order-service.users', 'users.events', 'user.updated');
        $channel->queue_bind('order-service.users', 'users.events', 'user.deleted');
    }
}
