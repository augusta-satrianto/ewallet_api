<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    /**
     * Send success response with JSON format.
     *
     * @param  array  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSuccessResponse(array $data, string $message, int $statusCode = 200, array $meta = []): JsonResponse
    {
        return response()->json([
            'meta' => array_merge([
                'status_code' => $statusCode,
                'message' => $message,
            ], $meta),
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Send error response with JSON format.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendErrorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'error' => $message,
        ], $statusCode);
    }

    protected function sendValidationErrorResponse(\Illuminate\Validation\ValidationException $exception): JsonResponse
    {
        $errors = $exception->errors();

        return response()->json([
            'status_code' => 422,
            'error' => 'Validation failed',
            'errors' => $errors,
        ], 422);
    }
}
