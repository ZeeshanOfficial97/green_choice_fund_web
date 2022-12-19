<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function PHPSTORM_META\type;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return $this->errorResponse('Record not found', $e, 404, $request->all());
            } else {
                return response()->view('custom.404', array(), 404);
            }
        });
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception|HttpException $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // if ($request->is('api/*')) {
        //     if ($exception instanceof TokenInvalidException) {
        //         return $this->errorResponse('Token is Invalid', code: 401, statusCode: 401);
        //     }
        //     if ($exception instanceof TokenExpiredException) {
        //         return $this->errorResponse('Token is Expired', code: 401, statusCode: 401);
        //     }
        //     if ($exception instanceof JWTException) {
        //         return $this->errorResponse('Token not parsed', code: 401, statusCode: 401);
        //     }
        //     return $this->handleApiException($request, $exception);
        // } else {
        //     return $this->exceptionResponse($exception);
        //     return response()->view('custom.500', array(), 500);
            return $retval = parent::render($request, $exception);
        // }
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }


    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];
        switch ($statusCode) {
            case 401:
                $response['message'] = 'Unauthorized';
                break;
            case 403:
                $response['message'] = 'Forbidden';
                break;
            case 404:
                $response['message'] = 'Record not found';
                break;
            case 405:
                $response['message'] = 'Method not allowed';
                break;
            case 422:
                $response['message'] = 'Validation failed'; //$exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            case 302:
                $response['message'] = 'Validation failed'; //$exception->original['message'];
                $response['errors'] = $exception;
                break;
            case 500:
                $response['message'] = 'Something went wrong. Please try again later or contact support';
                $response['errors'] = $exception;
                break;
            default:
                $response['message'] = 'Something went wrong. Please try again later or contact support';
                break;
        }

        //        if (config('app.debug')) {
        //            $response['trace'] = $exception->getTrace();
        //            $response['code'] = $exception->getCode();
        //        }

        $message = $response['message'];
        $errors = isset($response['errors']) ? $response['errors'] : null;

        return $this->errorResponse($message, $errors, $statusCode);
    }
}
