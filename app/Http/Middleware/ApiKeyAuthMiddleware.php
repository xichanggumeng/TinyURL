<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = env('api_key');
        $requestKey = $request->input('token');
        if ($requestKey !== $apiKey) {
            abort(401); // 鉴权失败，返回 401 Unauthorized 响应
        }

        return $next($request);
    }
}
