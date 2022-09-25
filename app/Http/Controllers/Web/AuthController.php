<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequests\Web\LoginRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;



class AuthController extends ApiController
{

    public function login(LoginRequest $request)
    {
        $invalidCredentials = [
            'password' => 'Sorry, we do not recognize these credentials'
        ];


        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->errorResponse($invalidCredentials['password'], $invalidCredentials, 610);
        }

        $user = User::where(['email' => $credentials['email']])->with('roles')->first();
        if ($user == null) {
            return $this->errorResponse($invalidCredentials['password'], $invalidCredentials, 610);
            if (!$user->status) {
                $errors = ['disabled' => ['This account has been disabled. Please contact support']];
                return $this->errorResponse($errors['disabled'], $errors, 610);
            }
        }

        $roleNames = $user->roles->pluck('name')->all();

        $data = [
            'accessToken' => $token,
            'user' => $user,
            'refreshToken' => $token
        ];

        return $this->successResponse("User logged in successfully", $data);
    }

}
