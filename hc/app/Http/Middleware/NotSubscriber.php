<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotSubscriber
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role == 'subscriber') {
            abort(403, 'Subscribers are not allowed to access this resource.');
        }

        return $next($request);
    }
}