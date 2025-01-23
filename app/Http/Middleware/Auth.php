<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Auth extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            abort(response()->json(['message' => 'Unauthorized'], 401));
        }

        return redirect()->guest(route('login')); // Redirect ke halaman login
    }
}
