<?php

namespace App\Http\Controllers\auth;

use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRoles::ADMIN
        ]);

        $token = JwtService::generateToken([
            'id'   => $user->id,
            'role' => $user->role,
        ]);

        return $this->succes(['token' => $token], 201);
    }


    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->error(['message' => 'Invalid credentials'], 401);
        }

        $token = JwtService::generateToken([
            'id'   => $user->id,
            'role' => $user->role,
        ]);

        return $this->succes(['token' => $token]);
    }

    public function verify(Request $request)
    {
        $auth = $request->header('Authorization');

        if (! $auth || ! str_starts_with($auth, 'Bearer ')) {
            return response()->json([], 401);
        }

        try {
            $token = str_replace('Bearer ', '', $auth);

            Log::info('auth creds', ['token'=> $token, 'auth' => $auth]);

            $decoded = JwtService::verify($token);

            return response()->json([], 200, [
                'X-User-Id'   => $decoded['data']->id,
                'X-User-Role' => $decoded['data']->role,
            ]);
        } catch (\Throwable $e) {
            return response()->json([], 401);
        }
    }
}
