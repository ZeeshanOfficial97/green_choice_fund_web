<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{

    protected function successResponse($message = null, $data = null, $title = 'Success', $code = 200, $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'title' => $title,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $statusCode);
    }

    protected function errorResponse($message = null, $errors = null, $code = 200, $title = 'Error', $data = null, $statusCode = 200)
    {
        return response()->json([
            'status' => 'error',
            'title' => $title,
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'errors' => $errors
        ], $statusCode);
    }
    protected function exceptionResponse(
        $data = null,
        $message = 'Something went wrong. Please try again later or contact support',
        $title = 'Error',
        $code = 500,
        $statusCode = 500
    ) {
        return response()->json([
            'status' => 'exception',
            'title' => $title,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $statusCode);
    }


    protected function mediaResponse($path)
    {
        $data = ['path' => $path, 'url' => $this->pathToUrl($path)];
        return $this->successResponse("Media upload successfully", $data);
    }

    private function pathToUrl($path)
    {
        return env('APP_URL') . '/storage/app/' . $path;
    }
}
