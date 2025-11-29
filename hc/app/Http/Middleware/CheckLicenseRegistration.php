<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\License;
use App\Models\ApiKey;

class CheckLicenseRegistration
{
    public function handle(Request $request, Closure $next)
    {
        // Skip for license registration routes
        if ($request->is('register-domain') || $request->is('license-required')) {
            return $next($request);
        }

        $apiKey = $request->header('X-API-KEY') ?? $request->query('api_key');
        $licenseKey = $request->header('X-LICENSE-KEY') ?? $request->query('license_key');
        $domain = $request->getHost();

        if (!$apiKey || !$licenseKey) {
            return redirect()->route('license.required', ['domain' => $domain]);
        }

        // Verify license
        $license = License::where('license_key', $licenseKey)
                        ->whereHas('apiKey', function($query) use ($apiKey) {
                            $query->where('api_key', $apiKey)->where('is_active', true);
                        })
                        ->first();

        if (!$license || !$license->is_active || $license->expires_at < now()) {
            return redirect()->route('license.required', ['domain' => $domain]);
        }

        // Check domain registration
        $normalizedDomain = $this->normalizeDomain($domain);
        $licenseDomain = $this->normalizeDomain($license->domain);

        if ($licenseDomain !== $normalizedDomain) {
            return redirect()->route('license.register.form', ['domain' => $domain]);
        }

        return $next($request);
    }

    private function normalizeDomain($domain)
    {
        $domain = str_replace(['http://', 'https://', 'www.'], '', $domain);
        $domain = explode('/', $domain)[0];
        return strtolower(trim($domain));
    }
}