<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQUserService
{
    private string $exchange = 'users.events';

    public function publishMessage(string $event, array $data): void
    {
        $connection = new AMQPStreamConnection(
            config('services.rabbitmq.host'),
            config('services.rabbitmq.port'),
            config('services.rabbitmq.user'),
            config('services.rabbitmq.password'),
            config('services.rabbitmq.vhost')
        );

        $channel = $connection->channel();

        $channel->exchange_declare($this->exchange, 'topic', false, true, false);

        $payload = json_encode([
            'event'       => $event,
            'data'        => $data,
            'version'     => 1,
            'occurred_at' => now()->toIso8601String(),
        ], JSON_THROW_ON_ERROR);

        $msg = new AMQPMessage($payload, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $channel->basic_publish($msg, $this->exchange, $event);

        $channel->close();
        $connection->close();
    }
}
