<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use GuzzleHttp\Psr7\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['message' => 'Login successful'], 200);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        } elseif ($request->has('email')) {
            $user->email = $request->email;
        } else {
            return response([
                'message' => 'No valid fields to update',
                'user' => $user,
            ], 400);
        }
        $user->save();

        return response([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

    public function delete(User $user)
    {
        $user->delete();

        return response([
            'message' => 'User deleted successfully',
        ], 200);
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response([
            'message' => 'Users retrieved successfully',
            'users' => $users,
        ], 200);
    }

    public function getUserById(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response([
                'message' => 'User not found',
            ], 404);
        }
        return response([
            'message' => 'User retrieved successfully',
            'user' => $user,
        ], 200);
    }
}
