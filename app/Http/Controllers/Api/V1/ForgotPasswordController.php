<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\AuthRequests\V1\AppUserForgotPasswordRequest;



class ForgotPasswordController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(AppUserForgotPasswordRequest $request)
    {

        // try {
            // $validate = $this->validator($request->all())->validate();


            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            if ($response == 'passwords.sent') {
                return $this->successResponse('Reset Password link has been sent to your email. Please check your inbox or spam folder', true);
            }
            return $this->successResponse('Reset Password link has been sent to your email. Please check your inbox or spam folder', true);
            // return response()->json(['status'    => false, 'message' => 'email is invalid']);
        // } catch (\Throwable $th) {
        //     return $this->exceptionResponse($th);
        // }
    }

    // protected function validator(array $data)
    // {
    //     $validator = Validator::make($data, ['email' => 'required|email']);

    //     if ($validator->fails()) {
    //         throw new BadRequestHttpException($validator->getMessageBag());
    //     }

    //     return $validator;
    // }
}
