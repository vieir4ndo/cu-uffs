<?php

namespace App\Http\Middleware;

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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$key = $request->get('api_key') or $key != config('app.api_key')){
            throw new AuthenticationException('Invalid API key');
        }
        return $next($request);
    }
}
