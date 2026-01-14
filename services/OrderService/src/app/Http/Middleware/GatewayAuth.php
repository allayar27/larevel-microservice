<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GatewayAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id');

        if (!$userId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->merge([
            'auth_user_id' => (int) $userId,
            'auth_user_role' => $request->header('X-User-Role'),
        ]);

        return $next($request);
    }
}
