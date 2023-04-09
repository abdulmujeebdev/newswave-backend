<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LogoutRequest;
use App\Http\Requests\UserRequest;
use App\Interfaces\AuthInterface;
use JWTAuth;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\UpdateProfileRequest;

class AuthController extends Controller
{
    /**
     * @var AuthInterface
     */
    protected $authInterface;
    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authInterface->login($request);

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'Logged in successfully.',
        ], Response::HTTP_OK);
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authInterface->register($request);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->authInterface->updateProfile($request);

        return response()->json([
            'success' => true,
            'message' => 'Profile has been Updated Successfully',
            'user' => $user,
        ], Response::HTTP_OK);
    }

    public function get_user(UserRequest $request)
    {
        $user = JWTAuth::authenticate($request->token);

        return response()->json(['data' => $user]);
    }


    public function logout(LogoutRequest $request)
    {
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
}
