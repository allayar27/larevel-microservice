<?php

namespace App\Handlers;

use App\Models\UserRead;
use Illuminate\Support\Facades\Log;

final class UserDeletedHandler
{
    public function handle($payload): void
    {
        try {
            UserRead::where('user_id', $payload['data']['id'])->delete();
        } catch (\Throwable $e) {
            Log::error('failed create user ' . $e->getMessage());
        }
    }
}
