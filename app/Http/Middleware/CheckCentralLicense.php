<?php

// app/Http/Middleware/CheckCentralLicense.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CheckCentralLicense
{
    public function handle(Request $request, Closure $next)
    {
        $licenseKey = config('app.license_key');
        $apiKey = config('app.license_api_key');
        $domain = $request->getHost();
        
        try {
            $response = Http::post('https://license-server.example.com/api/verify-license', [
                'domain' => $domain,
                'license_key' => $licenseKey,
                'api_key' => $apiKey
            ]);
            
            if ($response->successful() && $response->json('valid')) {
                return $next($request);
            }
            
        } catch (\Exception $e) {
            Log::error('License verification failed: ' . $e->getMessage());
        }
        
        return redirect()->away('https://license-server.example.com/license-required?domain=' . urlencode($domain));
    }
}