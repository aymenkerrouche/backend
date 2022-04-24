<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user already exists
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response('The provided email already exists.', 403);
            // throw ValidationException::withMessages([
            //     'email' => ['The provided email already exists.'],
            // ]);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);


        $user = User::create($input);

        $response['token'] = $user->createToken($request->email)->plainTextToken;
        $response['user'] = $user;
        return response(json_encode($response), 201);
    }

    public function login(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response('The provided credentials are incorrect.', 403);
        }

        $response['token'] =  $user->createToken($request->email)->plainTextToken;
        $response['user'] = $user;
        return response(json_encode($response));
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];
    }
}
