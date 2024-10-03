<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_logs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'credentials not found ',
            ];
            return response()->json($data);
        }

        if (Hash::check($request->input('password'), $user->Password)) {

            if ($user->Is_active == '0') {
                $user->Is_active = '1';
                $user->save();
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $user_logs = User_logs::where('token', $token)->first();

            $data = [
                'status' => 200,
                'message' => 'success',
                'token_type' => 'Bearer',
                'access_token' => $token,
                'details' => [
                    'email' => $user->Email,
                    'password' => $user->Password,
                    'usertype' => (int) $user->UserType,
                    'is_active' => (int) $user->Is_active,
                    'status' => $user->Status,
                ]
            ];
            return response()->json($data);
        } else {
            $data = [
                'status' => 401,
                'message' => 'password not matched',
            ];
            return response()->json($data);
        }
    }

    public function logout(Request $request)
    {

        $user = $request->user();
        $user->Is_active = '0';
        $user->save();

        // $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'usertype' => 'required',
            'is_active' => 'required',
            'status' => 'required',


        ]);

        // Return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the user
        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'usertype' => $request->input('usertype'),
            'is_active' => $request->input('is_active'),
            'status' => $request->input('status'),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }
}
