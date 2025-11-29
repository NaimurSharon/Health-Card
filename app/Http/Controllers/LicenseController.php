<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class LicenseController extends Controller
{
    public function showForm()
    {
        return view('license.activate');
    }

    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'api_key' => 'required|string',
            'license_server_url' => 'required|url'
        ]);

        // Path to the .env file
        $envPath = base_path('.env');

        // Read the current .env content
        $envContent = File::get($envPath);

        // Update or add the license keys
        $envContent = preg_replace([
            '/LICENSE_KEY=.*/',
            '/LICENSE_API_KEY=.*/',
            '/LICENSE_SERVER_URL=.*/'
        ], [
            'LICENSE_KEY=' . $request->license_key,
            'LICENSE_API_KEY=' . $request->api_key,
            'LICENSE_SERVER_URL=' . $request->license_server_url
        ], $envContent);

        // If keys don't exist, append them
        if (!str_contains($envContent, 'LICENSE_KEY=')) {
            $envContent .= "\nLICENSE_KEY={$request->license_key}\n";
        }
        if (!str_contains($envContent, 'LICENSE_API_KEY=')) {
            $envContent .= "\nLICENSE_API_KEY={$request->api_key}\n";
        }
        if (!str_contains($envContent, 'LICENSE_SERVER_URL=')) {
            $envContent .= "\nLICENSE_SERVER_URL={$request->license_server_url}\n";
        }

        // Save the updated .env file
        File::put($envPath, $envContent);

        // Clear config cache
        Artisan::call('config:clear');

        return redirect()->route('license.activate.success');
    }
    
    public function activationSuccess()
    {
        return view('license.success');
    }
}