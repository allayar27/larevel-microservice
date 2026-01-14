<?php

namespace App\Providers;

use App\Jobs\OrderCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected  $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
    }
}
