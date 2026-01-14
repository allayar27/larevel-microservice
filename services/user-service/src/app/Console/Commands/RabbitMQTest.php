<?php

namespace App\Console\Commands;


use App\Services\RabbitMQUserService;
use Illuminate\Console\Command;

class RabbitMQTest extends Command
{
    protected $signature = 'rabbitmq:test';

    protected $description = '';

    public function handle(RabbitMQUserService $service)
    {
        $this->info('RabbitMQ consumer started');

        // $service->publishMessage('user.events', 'user.created', 'hello world');

        $this->info('RabbitMQ msg published');
    }
}
