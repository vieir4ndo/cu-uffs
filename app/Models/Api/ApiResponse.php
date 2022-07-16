<?php

namespace App\Models\Api;

class ApiResponse
{
    public static function ok($data, $success = true): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "success" => $success,
            "data" => $data,
            "messages" => null
        ], 200);
    }

    public static function badRequest($messages): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "success" => false,
            "data" => null,
            "messages" => $messages
        ], 400);
    }

    public static function conflict($messages): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "success" => false,
            "data" => null,
            "messages" => $messages
        ], 409);
    }

    public static function noContent($messages): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "success" => true,
            "data" => null,
            "messages" => $messages
        ], 204);
    }

    public static function accepted($data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "success" => true,
            "data" => $data,
            "messages" => null
        ], 202);
    }
}
