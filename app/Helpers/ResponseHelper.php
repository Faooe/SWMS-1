<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Success Response
     */
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {

        return response()->json([

            'success' => true,

            'message' => $message,

            'data' => $data,

        ], $status);

    }

    /**
     * Error Response
     */
    public static function error(
        string $message = 'Error',
        mixed $errors = null,
        int $status = 400
    ): JsonResponse {

        return response()->json([

            'success' => false,

            'message' => $message,

            'errors' => $errors,

        ], $status);

    }
}