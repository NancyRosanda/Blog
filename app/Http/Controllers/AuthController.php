<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $token = auth('api')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        return $token ? new UserResource(auth('api')->user()) : abort(401);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return new UserResource($user);
    }


}
