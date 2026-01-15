<?php

namespace App\Services;

use App\Enums\ProductEvent;
use App\Handlers\ProductCreatedEventHandler;
use App\Handlers\ProductDeletedEventHandler;
use App\Handlers\ProductUpdatedEventHandler;
use App\Handlers\UserCreatedEventHandler;
use App\Handlers\UserDeletedEventHandler;
use App\Handlers\UserUpdatedEventHandler;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ProductEventService
{
    public function publish(string $event, array $data): void
    {
        $connection = $this->setConnection();
        $channel = $connection->channel();

        $channel->exchange_declare('order.events', 'direct', false, true, false);

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

        $channel->basic_publish($msg, 'order.events', $event);

        $channel->close();
        $connection->close();
    }

    public function dispatch(AMQPMessage $msg)
    {
        $payload = json_decode($msg->body, true);

        $event = $event = ProductEvent::fromPayload($payload);

        Log::info('products payload', [$payload]);

        match ($event) {
            ProductEvent::CREATED => app(ProductCreatedEventHandler::class)->handle($payload),
            ProductEvent::UPDATED => app(ProductUpdatedEventHandler::class)->handle($payload),
            ProductEvent::DELETED => app(ProductDeletedEventHandler::class)->handle($payload),
            default => logger()->warning('Unknown event', $payload),
        };

        $msg->ack();
    }

    public function setConnection(): ?AMQPStreamConnection
    {
        try {
            return new AMQPStreamConnection(
                config('services.rabbitmq.host'),
                config('services.rabbitmq.port'),
                config('services.rabbitmq.user'),
                config('services.rabbitmq.password'),
                config('services.rabbitmq.vhost')
            );
        } catch (\Throwable $e) {
            Log::error('failed set connection: ' . $e->getMessage());
            return null;
        }
    }
}
