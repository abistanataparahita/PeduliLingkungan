<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_banned) {
            return back()->with('error', 'Akun kamu dibatasi. Kamu tidak dapat membuat post atau membalas diskusi.');
        }

        return $next($request);
    }
}

