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
        $apiKey = config('app.api_key'); // 从配置文件获取 API_KEY
        $requestKey = $request->all('token'); // 获取请求中的 key 参数

        if ($requestKey !== $apiKey) {
            return response('Unauthorized', 401); // 鉴权失败，返回 401 Unauthorized 响应
        }

        return $next($request);
    }
}
