<?php

namespace App\Handlers;

use App\Models\UserRead;
use Illuminate\Support\Facades\Log;

final class UserUpdatedHandler
{
    public function handle($payload): void
    {
        $data = $payload['data'];

        try {

            UserRead::updateOrCreate(
            ['user_id' => $data['id']],
            [
                    'name'  => $data['name'],
                    'email' => $data['email'],
                    'role'  => $data['role'],
                ]
            );

        } catch (\Throwable $e) {
            Log::error('failed create user ' . $e->getMessage());
        }
    }
}
