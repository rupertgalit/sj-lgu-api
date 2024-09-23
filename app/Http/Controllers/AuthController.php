<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !User::where($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->Is_active == '0') {
            $user->Is_active = '1';
            $user->save();
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
            'status' => 200,
            'message' => 'success',
            'access_token' => $token,
            'details' => [
                'username' => $user->Username,
                'password' => $user->Password,
                'usertype' => $user->UserType,
                'is_active' => $user->Is_active,
                'status' => $user->Status
            ],
        ];
        return response()->json([
            $data
        ]);
    }

    public function logout(Request $request)
    {

        $user = $request->user();
        $user->Is_active = '0';
        $user->save();

        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
