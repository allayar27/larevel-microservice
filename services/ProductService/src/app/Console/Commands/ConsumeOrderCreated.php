<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeOrderCreated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume-orders';

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
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );

        $channel = $connection->channel();
        $channel->queue_declare('order.created', false, true, false, false);

        $callback = function (AMQPMessage $msg) {
            $payload = json_decode($msg->body, true);

            if ($payload['event'] === 'order.created') {
                $data = $payload['data'];

                $product = Product::find($data['product_id']);
                if ($product) {
                    $product->decrement('inventory', $data['count']);
                }
            }

            $msg->ack();
        };

        $channel->basic_consume('order.created', '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
