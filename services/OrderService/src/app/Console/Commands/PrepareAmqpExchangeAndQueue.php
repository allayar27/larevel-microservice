<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class PrepareAmqpExchangeAndQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:amqp-setting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $connection = new AMQPStreamConnection(
            config('services.rabbitmq.host'),
            config('services.rabbitmq.port'),
            config('services.rabbitmq.user'),
            config('services.rabbitmq.password'),
            config('services.rabbitmq.vhost')
        );

        $channel = $connection->channel();

        $channel->exchange_declare('order.events', 'direct', false, true, false);
        $channel->queue_declare('product.order.created', false, true, false, false);
        $channel->queue_bind('product.order.created', 'order.events', 'order.created');

        echo "Exchange && queue created and successfully binded" . PHP_EOL;

        $channel->close();
        $connection->close();
    }
}
