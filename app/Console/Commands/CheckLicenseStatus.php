<?php
// app/Console/Commands/CheckLicenseStatus.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CheckLicenseStatus extends Command
{
    protected $signature = 'license:check';
    protected $description = 'Check license status with central server';

    public function handle()
    {
        $licenseKey = config('app.license_key');
        $apiKey = config('app.license_api_key');
        $domain = parse_url(config('app.url'), PHP_URL_HOST);
        
        try {
            $response = Http::post(config('app.license_server_url').'/api/verify-license', [
                'domain' => $domain,
                'license_key' => $licenseKey,
                'api_key' => $apiKey
            ]);
            
            Cache::put('license_status', $response->json(), now()->addHours(12));
            
        } catch (\Exception $e) {
            Log::error('License check failed: '.$e->getMessage());
        }
    }
}