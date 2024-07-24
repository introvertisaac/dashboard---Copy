<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->header('php-auth-user');
        $api_secret = $request->header('php-auth-pw');

        $user = User::where('username', $username)->where('api_secret', $api_secret)->first();

        if ($user) {

            $request->setUserResolver(function () use ($user) {
                return $user;
            });

            return $next($request);
        }

        return response()->json([
            'success' => false,
            'response_code' => 401,
            'message' => 'Authentication failure, check credentials',
            'data' => [],
            'request_id' => Str::uuid()
        ]);

    }
}
