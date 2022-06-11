<?php

namespace App\Models\Api;

use Illuminate\Http\Response;

class ApiResponse
{
    public static function ok($data)
    {
        return response()->json([
            "success" => true,
            "data" => $data,
            "errors" => null
        ], 200);
    }

    public static function badRequest($error)
    {
        return response()->json([
            "success" => false,
            "data" => null,
            "errors" => $error
        ], 400);
    }
}
