<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class PublishOrderCreated extends Command
{
    protected $signature = 'app:publish-order-created';

    protected $description = 'Command description';

    public function handle(RabbitMQService $rabbitMQService)
    {
        
    }
}
