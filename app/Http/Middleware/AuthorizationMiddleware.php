<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        $base64Token = 'U3VsdGhvbiBOYWZpcw=='; // Token Statis Sulthon Nafis

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Authorization header is missing or invalid'], 401);
        }

        $token = substr($authHeader, 7); // Hapus prefix "Bearer "

        // Validasi Token Statis
        if ($token === $base64Token) {
            return $next($request);
        }

        // Validasi Token Login
        $decodedToken = base64_decode($token);
        $tokenParts = explode('|', $decodedToken);

        if (count($tokenParts) !== 2 || !is_numeric($tokenParts[0])) {
            return response()->json(['message' => 'Invalid token'], 403);
        }

        $userId = $tokenParts[0];
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Invalid user'], 403);
        }

        Auth::login($user);

        return $next($request);
    }
}
