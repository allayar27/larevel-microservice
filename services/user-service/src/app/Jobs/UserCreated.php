<?php

namespace App\Jobs;

use App\Services\RabbitMQUserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UserCreated implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function __construct(public array $data)
    {
    }

    public function handle(): void
    {

    }
}
