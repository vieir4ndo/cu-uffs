<?php

namespace App\Http\Middleware;

use App\Models\Api\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);
        } catch (\Exception) {
            return ApiResponse::badRequest($e->getMessage());
        }

        return $response;
    }
}
