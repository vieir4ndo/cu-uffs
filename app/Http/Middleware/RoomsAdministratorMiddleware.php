<?php

namespace App\Http\Middleware;

use App\Models\Api\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class RoomsAdministratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->isRoomsAdministrator()){
            return $next($request);
        }
        return ApiResponse::forbidden('User is not allowed to do this operation.');
    }
}
