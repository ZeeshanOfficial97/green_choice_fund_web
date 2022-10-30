<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequests\V1\AppUserLoginRequest;
use App\Http\Requests\AuthRequests\V1\AppUserSignupRequest;
use App\Http\Requests\AuthRequests\V1\AppUserUpdatePasswordRequest;
use App\Http\Requests\AuthRequests\V1\AppUserUpdateRequest;
use App\Http\Requests\AuthRequests\V1\AppUserSocialLoginRequest;

use App\Models\InfoUrl;
use App\Models\User;
use App\Models\SocialAccount;
use App\Models\PushNotificationUser;
use App\Models\PushNotificationPlatform;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// use Socialite;
use Laravel\Socialite\Facades\Socialite;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\StripeClient;
use Exception;
use Namshi\JOSE\JWT;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class AuthController extends ApiController
{
    use StripeClient;

    public function login(AppUserLoginRequest $request)
    {
        $invalidCredentials = [
            'password' => 'Sorry, we do not recognize these credentials'
        ];

        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->errorResponse($invalidCredentials['password'], $invalidCredentials, 610);
        }

        if ($request->push_platform_id == null) {
            $errors = [
                'push_platform_id' => ['Platform id is required']
            ];
            return $this->errorResponse($errors['push_platform_id'], $errors, 610);
        }

        if (!in_array($request->push_platform_id, [1, 2])) {
            $errors = [
                'push_platform_id' => ['Platform id is required']
            ];
            return $this->errorResponse($errors['push_platform_id'], $errors, 610);
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
        $rolesForUserInDB = ['app_user'];
        if (!array_intersect($roleNames, $rolesForUserInDB)) {
            return $this->errorResponse($invalidCredentials['password'], $invalidCredentials, 610);
        }

        $push_notifications = PushNotificationUser::updateOrCreate(['device_id' => $request->device_id], [
            'device_token' => $request->device_token,
            'device_id' => $request->device_id,
            'user_id' => $user->id,
            'status' => true,
            'date_updated' => \Carbon\Carbon::now(),
            'push_platform_id' => $request->push_platform_id
        ]);

        $data = [
            'token' => $token,
            'user' => $user,
            'push_notification' => $push_notifications
        ];

        return $this->successResponse("User logged in successfully", $data);
    }

    public function signup(AppUserSignupRequest $request)
    {
        $stripeUser = null;

        try {
            if (!in_array($request->push_platform_id, [1, 2])) {
                $errors = [
                    'push_platform_id' => ['Invalid platform id']
                ];
                return $this->errorResponse("Invalid platform id", $errors, 610, $errors);
            }

            $data = [
                'name' => $request['name'],
                'email' => $request['email'],
                'password'  =>  bcrypt($request['password']),
                'country_code' => $request['country_code'],
                'contact_no' => $request['contact_no'],
                'profile_photo_path' => '',
                'email_verified_at' => \Carbon\Carbon::now(),
                'privacy_policy_version' => config('app_green_choice_fund.privacy_policy_version', '1.0'),
                'is_notification_enabled' => true,
                'user_type_id' => $request['user_type_id'],
                'status' => true
            ];

            $stripe = $this->getStripeClient();
            $stripeUser = $stripe->customers->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['country_code'] . ' ' . $data['contact_no'],
                'description' => "greenchoicefund.com platform's user",
            ]);

            $data['stripe_user_id'] = $stripeUser->id;

            DB::beginTransaction();
            $app_user = Role::findByName('app_user', 'api');
            if ($app_user == null) {
                throw new Exception();
            }
            $userCreated = User::create($data)->assignRole($app_user);

            $push_notifications = PushNotificationUser::updateOrCreate(['device_token' => $request->device_token], [
                'device_token' => $request->device_token,
                'device_id' => $request->device_id,
                'user_id' => $userCreated->id,
                'status' => true,
                'date_updated' => \Carbon\Carbon::now(),
                'push_platform_id' => $request->push_platform_id
            ]);

            DB::commit();

            $credentials = $request->only(['email', 'password']);
            if (!$token = auth('api')->attempt($credentials)) {
                return $this->errorResponse("Sorry, we do not recognize these credentials", null, 610);
            }

            $data = [
                'token' => $token,
                'user' => $userCreated,
                'push_notification' => $push_notifications
            ];


            return $this->successResponse("User logged in successfully", $data);
        } catch (\Throwable $th) {
            if (isset($stripeUser->id)) {
                try {
                    $stripe = $this->getStripeClient();
                    $stripe->customers->delete(
                        $stripeUser->id,
                        []
                    );
                } catch (\Throwable $th) {
                    return $this->exceptionResponse($th);
                }
            }
            return $this->exceptionResponse($th);
        }
    }

    public function userDetail(User $user)
    {
        try {
            $user = User::where('id', Auth::id())->with('roles')->first();
            return $this->successResponse("User data", $user);
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function userDelete(Request $request)
    {
        try {
            if ($user = User::where('id', Auth::id())->first()) {
                if ($request->get('password') != null) {
                    if (Hash::check($request->get('password'), $user->password)) {
                        DB::beginTransaction();

                        PushNotificationUser::where('user_id', $user->id)->forceDelete();
                        $user->delete();

                        auth('api')->logout();
                        $forever = true;
                        JWTAuth::parseToken()->invalidate($forever);

                        DB::commit();
                        return $this->successResponse("User data deleted successfully", true);
                    } else {
                        return $this->errorResponse("Sorry, we do not recognize these credentials", null, 610);
                    }
                }
            }
            return $this->errorResponse("User not found", null, 404, statusCode: 404);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }

    public function updatePassword(AppUserUpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();
            $newPassword = $request->new_password;
            if (!Hash::check($request->old_password, $user->password)) {
                $errors = ['password' => ['Incorrect old password']];
                return $this->errorResponse('Incorrect old password',  $errors['password'], 610);
            }

            User::find($user->id)->update(['password' => bcrypt($newPassword)]);
            return $this->successResponse("Password details updated successfully", true);
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function updateUser(AppUserUpdateRequest $request)
    {
        try {

            $requestData = $request->only(['name', 'country_code', 'contact_no', 'privacy_policy_version', 'user_type_id']);
            $data = $this->removeNullValues($requestData);

            if ($user = User::find(Auth::id())) {
                if ($user === null) {
                    $errors = ['user' => ['User not found']];
                    return $this->errorResponse($errors['user'], $errors, 404);
                }
                if (isset($data['name'])) {
                    $user->name = $data['name'];
                }
                if (isset($data['country_code']) && isset($data['contact_no'])) {
                    $user->country_code = $data['country_code'];
                    $user->contact_no = $data['contact_no'];
                }

                if (isset($data['privacy_policy_version'])) {
                    $user->privacy_policy_version = $data['privacy_policy_version'];
                }

                if (isset($data['user_type_id']) && $user->user_type_id == null) {
                    $user->user_type_id = $data['user_type_id'];
                }

                $user->save();

                return $this->successResponse('User updated successfully', $user);
            }
            return $this->errorResponse("User not found", null, 404, statusCode: 404);
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function userNotificationEnabled(Request $request)
    {
        try {
            if (!$request->has('enable')) {
                $errors = ['enable' => ['Enable is required']];
                return $this->errorResponse($errors['enable'], $errors, 422);
            }
            $user = User::find(Auth::id());
            $user->is_notification_enabled = $request->enable == true || $request->enable == 1 ? true : false;
            $user->save();
            if ($request->enable == true || $request->enable) {
                return $this->successResponse("Notifications enabled successfully", $user);
            } else {
                return $this->successResponse("Notifications disabled successfully", $user);
            }
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function logout(Request $request)
    {
        try {
            // if (!$request->has('push_notification_id') || !$request->has('device_token') || !$request->has('device_id')) {
            //     $errors = [
            //         'push_notification_id' => ['Push notification id is required'],
            //         'device_token' => ['Device token is required'],
            //         'device_id' => ['Device id is required']
            //     ];
            //     return $this->errorResponse('Push notification id, device token or device id is required', $errors, 610);
            // }
            if ($request->has('push_notification_id')) {
                PushNotificationUser::where('id', $request['push_notification_id'])->forceDelete();
            } else if ($request->has('device_token')) {
                PushNotificationUser::where('device_token', $request['device_token'])->forceDelete();
            } else if ($request->has('device_id')) {
                PushNotificationUser::where('device_id', $request['device_id'])->forceDelete();
            }

            auth('api')->logout();
            $forever = true;
            JWTAuth::parseToken()->invalidate($forever);

            return $this->successResponse("User logged out successfully",true);
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function socialLogin(AppUserSocialLoginRequest $request)
    {
        // try {

        if ($request->push_platform_id == null) {
            $errors = [
                'push_platform_id' => ['Platform id is required']
            ];
            return $this->errorResponse($errors['push_platform_id'], $errors, 610);
        }

        if (!in_array($request->push_platform_id, [1, 2])) {
            $errors = [
                'push_platform_id' => ['Platform id is required']
            ];
            return $this->errorResponse($errors['push_platform_id'], $errors, 610);
        }

        $provider = $request->input('provider');

        switch ($provider) {
            case 'facebook':
                $social_user = Socialite::driver('facebook');
                break;
            case 'google':
                $social_user = Socialite::driver('google')->scopes(['profile', 'email']);
                break;
            case 'apple':
                $social_user = Socialite::driver('sign-in-with-apple')->scopes(['name', 'email']);
                break;
            default:
                $social_user = null;
        }

        if ($social_user == null) {
            $errors = ['provider' => ['Provider is invalid']];
            return $this->errorResponse($errors['provider'], $errors, 610);
        }

        Log::info('Log Start All');
        Log::info(json_encode($request->all()));
        Log::info('Log End All');
        $userInfo = null;
        if ($provider == 'google') {
            if ($request->get('user_info') == null) {
                return $this->errorResponse("User details not found");
            }
            $userInfo = $request->get('user_info');
        } else {
            try {
                $social_user_details = $social_user->userFromToken($request->input('access_token'));
            } catch (\Exception $e) {
                $errors = ['access_token' => ['Access token is invalid']];
                return $this->errorResponse($errors['access_token'], $errors, 610);
            }

            if ($social_user_details == null) {
                $errors = ['access_token' => ['Access token is invalid']];
                return $this->errorResponse($errors['access_token'], $errors, 610);
            }
        }

        $user = User::where("email", $provider == 'google' ? $userInfo['email'] : $social_user_details->getEmail())->first();

        DB::beginTransaction();

        if (!$user) {
            $social_account = SocialAccount::create([
                'provider' => $provider,
                'provider_user_id' => $provider == 'google' ? $userInfo['uid'] : $social_user_details->id
            ]);
            $user = new User;
            switch ($provider) {
                case 'facebook':
                    $user->name = $social_user_details->user['name'];
                    $user->profile_photo_path = $social_user_details->avatar;
                    break;
                case 'google':
                    $user->name = $userInfo['name'];
                    $user->email_verified_at = \Carbon\Carbon::now();
                    $user->profile_photo_path = $userInfo['picture'];

                    // $user->name = $social_user_details->user['name'];
                    // $user->profile_photo_path = $social_user_details->user['picture'];
                    break;
                case 'apple':
                    Log::info('Log Start Apple');
                    Log::info(json_encode($social_user_details));
                    Log::info('Log End Apple');
                    $user->name = $social_user_details->name ? $social_user_details->name : 'Apple User';
                    break;
            }


            $user->email = $provider == 'google' ? $userInfo['email'] : $social_user_details->getEmail();
            $user->password = Hash::make('green_choice_fund_with_socia_login');
            $user->social_account_id = $social_account->id;
            $user->privacy_policy_version = config('app_green_choice_fund.privacy_policy_version', '1.0');
            $user->is_notification_enabled = true;
            $user->status = true;

            $stripe = $this->getStripeClient();
            $stripeUser = $stripe->customers->create([
                'name' => $user->name,
                'email' => $user->email,
                'description' => "greenchoicefund.com platform's user",
            ]);
            $user->stripe_user_id = $stripeUser->id;

            $user->save();
            $app_user = Role::findByName('app_user', 'api');
            if ($app_user == null) {
                throw new Exception();
            }
            $user->assignRole($app_user);
        }
        $userInDB = User::where("email", $provider == 'google' ? $userInfo['email'] : $social_user_details->getEmail())->first();

        if (!$userInDB->status) {
            $errors = ['disabled' => ['This account has been disabled. Please contact support']];
            return $this->errorResponse($errors['disabled'], $errors, 610);
        }

        if ($userInDB->social_account_id == null) {
            return $this->errorResponse('This email is already registered. Use email and password to login', null, 610);
        }


        if (!Hash::check('green_choice_fund_with_socia_login', $userInDB->password)) {
            $errors = ['password' => ['Incorrect old password']];
            return $this->errorResponse('Password is already set for this account. Use email and password to login', null, 610);
        }

        $push_notifications = PushNotificationUser::updateOrCreate(['device_token' => $request->device_token], [
            'device_token' => $request->device_token,
            'device_id' => $request->device_id,
            'user_id' => $userInDB->id,
            'status' => true,
            'date_updated' => \Carbon\Carbon::now(),
            'push_platform_id' => $request->push_platform_id
        ]);

        DB::commit();

        $token = JWTAuth::fromUser($userInDB);
        $data = [
            'token' => $token,
            'user' => $userInDB,
            'push_notification' => $push_notifications
        ];

        return $this->successResponse("User logged in successfully", $data);
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }
}
