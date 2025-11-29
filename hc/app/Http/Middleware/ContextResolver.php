<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class ContextResolver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $contextBase24 = 'ZmFjcmVhdGl2ZWZpcm0uY29tLHRyYWRlLmZhY3JlYXRpdmVmaXJtLmNvbQ==';
        $contextResolver = explode(',', base64_decode($contextBase24));


        if (!in_array($request->getHost(), $contextResolver)) {
    return redirect(URL::temporarySignedRoute(
        'license.required',
        now()->addMinutes(30), // Link expires in 30 minutes
        ['ref' => Str::random(8)]
    ));
}

        return $next($request);
    }
}
