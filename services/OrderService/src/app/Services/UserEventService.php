<?php

namespace App\Services;

use App\Enums\Enums\UserEvent;
use App\Handlers\UserCreatedEventHandler;
use App\Handlers\UserDeletedEventHandler;
use App\Handlers\UserUpdatedEventHandler;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class UserEventService
{
    public function dispatch(AMQPMessage $msg)
    {
        $payload = json_decode($msg->body, true);

        $event = $event = UserEvent::fromPayload($payload);

        Log::info('users payload', [$payload]);

        match ($event) {
            UserEvent::CREATED => app(UserCreatedEventHandler::class)->handle($payload),
            UserEvent::UPDATED => app(UserUpdatedEventHandler::class)->handle($payload),
            UserEvent::DELETED => app(UserDeletedEventHandler::class)->handle($payload),
            default => logger()->warning('Unknown event', $payload),
        };

        $msg->ack();
    }

    public function setConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            config('services.rabbitmq.host'),
            config('services.rabbitmq.port'),
            config('services.rabbitmq.user'),
            config('services.rabbitmq.password'),
            config('services.rabbitmq.vhost')
        );
    }
}
