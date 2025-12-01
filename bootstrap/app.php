<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load admin routes
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
            
            // Load API routes
            Route::middleware('web')
                ->group(base_path('routes/api.php'));
            
            // Load role-specific route files
            Route::middleware('web')
                ->group(base_path('routes/student.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/doctor.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/teacher.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/principal.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
