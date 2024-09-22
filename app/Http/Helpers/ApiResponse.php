<?php

namespace App\Http\Helpers;

class ApiResponse
{
    /**
     * Response untuk status 200 OK
     *
     * @param string $message
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = 'sucess')
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    /**
     * Response untuk status 201 Created
     *
     * @param string $message
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function created($data = null, $message = 'created')
    {
        return response()->json([
            'status' => 201,
            'message' => $message,
            'data' => $data
        ], 201);
    }

    /**
     * Response untuk status 400 Bad Request
     *
     * @param string $message
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function badRequest($data = null, $message = 'Bad request')
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
            'data' => $data
        ], 400);
    }

    /**
     * Response untuk status 404 Not Found
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function notFound($message = 'not found')
    {
        return response()->json([
            'status' => 404,
            'message' => $message,
            'data' => null
        ], 404);
    }
}
