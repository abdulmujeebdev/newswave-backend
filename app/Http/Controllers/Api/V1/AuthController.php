<?php

namespace App\Http\Controllers\Api\V1;

use JWTAuth;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Interfaces\AuthInterface;
use App\Http\Requests\UserRequest;
use Illuminate\Routing\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

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
        $data = $this->authInterface->login($request);

        return response()->json([
            'success' => Arr::get($data, 'success', true),
            'token' => Arr::get($data, 'token'),
            'user' => request()->user(),
            'message' => Arr::get($data, 'message'),
        ], Arr::get($data, 'code', Response::HTTP_OK));
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

    public function getUser()
    {
        return response()->json([
            'data' => request()->user()
        ]);
    }


    public function logout(Request $request)
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
