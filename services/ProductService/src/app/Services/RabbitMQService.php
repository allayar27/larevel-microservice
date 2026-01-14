<?php

namespace App\Services;

use App\Handlers\OrderCreatedHandler;
use App\RabbitMQ\EventRouter;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    public function publish(string $message, string $exchangeName, string $routingKey)
    {
        $connection = $this->setConnection();

        $channel = $connection->channel();
        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, $exchangeName, $routingKey);

        $channel->close();
        $connection->close();
    }

    public function consume(string $queueName)
    {
        $connection = $this->setConnection();

        $channel = $connection->channel();

        $channel->basic_qos(0,1,null);


        $channel->basic_consume($queueName, '', false, false, false, false, function (AMQPMessage $msg) use ($queueName) {
            try {
                $payload = json_decode($msg->body, true);

                match ($queueName) {
                    'product.order.created' => app(OrderCreatedHandler::class)->handle($payload),
                    default => logger()->warning('Unknown queue ' . $queueName, $payload),
                };
                // app(EventRouter::class)->route($payload);

                $msg->ack();
            } catch (\Throwable $e) {
                logger()->error('RabbitMQ consume failed', [
                    'error' => $e->getMessage(),
                    'payload' => $msg->body,
                ]);

                $msg->nack(false, false);
            }
        });

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    private function setConnection(): AMQPStreamConnection
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
