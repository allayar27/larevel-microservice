<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class RabbitMQConsume extends Command
{
    protected $signature = 'rabbitmq:consume';

    protected $description = 'Consume RabbitMQ events';

    public function handle(RabbitMQService $service)
    {
        
    }
}
