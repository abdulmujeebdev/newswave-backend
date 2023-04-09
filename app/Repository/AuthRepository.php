<?php

namespace App\Repository;

use JWTAuth;
use App\Models\User;
use App\Interfaces\AuthInterface;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthRepository implements AuthInterface {
    public function login($request) {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        return $token;
    }

    public function register($request) {
        return  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
    }

    public function updateProfile($request){
        $user = JWTAuth::authenticate($request->token);

        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }
}
