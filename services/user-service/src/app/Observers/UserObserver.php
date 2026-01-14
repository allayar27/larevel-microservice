<?php

namespace App\Observers;

use App\Jobs\UserCreated;
use App\Models\User;
use App\Services\RabbitMQUserService;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function __construct(
        protected RabbitMQUserService $rabbit
    ) {}

    public function created(User $user): void
    {
        $user->refresh();

        $this->rabbit->publishMessage(
            'user.created',
            [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        );


    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $user->refresh();

        $this->rabbit->publishMessage(
            'user.updated',
            [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        );
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->rabbit->publishMessage(
            'user.deleted',
            [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        );
    }


}
