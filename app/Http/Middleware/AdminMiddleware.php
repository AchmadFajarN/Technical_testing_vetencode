<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 

class AdminMiddleware
{

    // app/Http/Middleware/AdminMiddleware.php

public function handle(Request $request, Closure $next): Response
{
    if ($request->routeIs('api.*')) {
        $user = Auth::guard('sanctum')->user();
    } else {
        // Ganti Auth::guard('web')->user() dengan Auth::user()
        $user = Auth::user(); 
    }

    if ($user && $user->active) { 
        return $next($request);
    }

    if (!$request->expectsJson()) {
        return redirect('/login');
    }

    return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
}
}