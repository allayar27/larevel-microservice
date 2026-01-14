<?php

namespace App\Services;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class JwtService
{
    public static function generateToken(array $payload): string
    {
        $now = Carbon::now()->timestamp;
        $addMinutes = (int) config('jwt.expire_minutes');
        $expireAt = Carbon::now()->addMinutes($addMinutes)->timestamp;

        $tokenPayload = [
            'iss' => 'user-service',
            'iat' => $now,
            'exp' => $expireAt,
            'data' => $payload,
        ];

        return JWT::encode($tokenPayload, config('jwt.secret'), config('jwt.algorithm'));
    }

    public static function verify(string $token): array
    {
        try {
            return (array) JWT::decode(
            $token,
            new Key(config('jwt.secret'), config('jwt.algorithm')));
        } catch (\Exception $e) {
            Log::error("error msg from verify", [$e->getMessage()]);
            return [];
        }
    }
}
