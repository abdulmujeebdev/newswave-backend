<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LogoutRequest;
use App\Http\Requests\UserRequest;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\UpdateProfileRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
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

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'Logged in successfully.',
        ], Response::HTTP_OK);
    }

    public function register(RegisterRequest $request)
    {
        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function logout(LogoutRequest $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ], Response::HTTP_OK);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProfile(UpdateProfileRequest $requestData)
    {
        $user = JWTAuth::authenticate($requestData->token);

        $user->name = $requestData->name;
        $user->password = bcrypt($requestData->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile has been Updated Successfully',
            'user' => $user,
        ], Response::HTTP_OK);
    }

    public function get_user(UserRequest $request)
    {
        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }
}
