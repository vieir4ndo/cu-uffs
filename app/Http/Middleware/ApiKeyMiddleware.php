<?php

namespace App\Http\Middleware;

use App\Models\Api\ApiResponse;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->api_key;
        if($key == config('app.api_key')){
            return $next($request);
        }
        return ApiResponse::forbidden("Invalid API key.");
    }
}
