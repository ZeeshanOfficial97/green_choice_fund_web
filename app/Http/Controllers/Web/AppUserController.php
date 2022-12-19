<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\AppUser\Web\AppUserResourceCollection;
use App\Models\User;
use App\Services\Web\AppUserService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppUserController extends ApiController
{

    /**
     * @var appUserService
     */
    private $appUserService;

    /**
     * @param appUserService $appUserService
     */
    public function __construct(AppUserService $appUserService)
    {
        $this->appUserService = $appUserService;
    }

    public function getAppUsersList(Request $request)
    {
        $data = new AppUserResourceCollection($this->appUserService->getAppUsersList($request));
        return $this->successResponse("App Users list", $data);
    }

    public function userDetail(User $user)
    {
        try {
            $user = $user->with('roles')->where('id',$user->id)->first();
            if($user) {
                if($user->user_type_id) {
                    $user['user_type'] = User::USER_TYPE[$user->user_type_id];
                }
            }
            return $this->successResponse("User data", $user);
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

}
