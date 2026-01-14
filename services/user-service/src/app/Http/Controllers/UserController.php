<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::find(11);
        $role = $user->role;
        return $this->succes($role);
    }

    public function update(User $user, Request $request)
    {
        $user->update($request->all());

        return $this->succes($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->succes("deleted successfully");
    }
}
