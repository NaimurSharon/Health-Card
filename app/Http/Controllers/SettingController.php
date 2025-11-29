<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('backend.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_tagline' => 'nullable|string|max:255',
            'site_keywords' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:3',
            'contact_email' => 'required|email|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:5120',
            'phone_number' => 'nullable|string|max:255',
            'app_url' => 'nullable|string|max:255',
        ]);

        try {
            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                $logoPath = $this->handleImageUpload($request->file('site_logo'), 'logo');
                $this->deleteOldFile(setting('site_logo'));
                Setting::updateOrCreate(
                    ['key' => 'site_logo'],
                    ['value' => $logoPath]
                );
            }

            // Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                $faviconPath = $this->handleImageUpload($request->file('site_favicon'), 'favicon');
                $this->deleteOldFile(setting('site_favicon'));
                Setting::updateOrCreate(
                    ['key' => 'site_favicon'],
                    ['value' => $faviconPath]
                );
            }
            
            // Handle watermark upload - FIXED SECTION
            if ($request->hasFile('site_watermark')) {
                $watermarkPath = $this->handleWatermarkUpload($request->file('site_watermark'));
                $this->deleteOldFile(setting('site_watermark'));
                Setting::updateOrCreate(
                    ['key' => 'site_watermark'],
                    ['value' => $watermarkPath]
                );
            }

            // Update other settings
            foreach ($validated as $key => $value) {
                if (!in_array($key, ['site_logo', 'site_favicon', 'site_watermark']) && $value !== null) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }

            return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    // Special handler for watermark to preserve transparency
    protected function handleWatermarkUpload($file)
    {
        $baseName = 'watermark_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/settings');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $fileName = $baseName . '.' . $originalExtension;
        $file->move($destinationPath, $fileName);
        
        return 'settings/' . $fileName;
    }

    protected function handleImageUpload($file, $type)
    {
        $baseName = $type . '_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/settings');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        if (!file_exists($destinationPath . '/compressed')) {
            mkdir($destinationPath . '/compressed', 0755, true);
        }
    
        $originalName = $baseName . '.' . $originalExtension;
        $file->move($destinationPath, $originalName);
        
        $webpName = $baseName . '.webp';
        $webpPath = $destinationPath . '/compressed/' . $webpName;
        
        // Always use GD instead of Spatie/Image to avoid Imagick dependency
        $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $webpPath, $type === 'favicon' ? 80 : 60);
    
        // Delete original file
        unlink($destinationPath . '/' . $originalName);
    
        return 'settings/compressed/' . $webpName;
    }

    protected function deleteOldFile($path)
    {
        if (!$path) return;

        $filePath = public_path('storage/' . $path);
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Also check if there's an uncompressed version
        $uncompressedPath = str_replace('compressed/', '', $filePath);
        if (file_exists($uncompressedPath)) {
            unlink($uncompressedPath);
        }
    }

    protected function convertToWebpWithGD($sourcePath, $destinationPath, $quality = 60)
    {
        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            case 'webp':
                // If already webp, just copy the file
                return copy($sourcePath, $destinationPath);
            default:
                throw new \Exception("Unsupported image type: $extension");
        }
    
        // Convert and save as WebP
        $result = imagewebp($image, $destinationPath, $quality);
        imagedestroy($image);
        
        if (!$result) {
            throw new \Exception("Failed to convert image to WebP");
        }
        
        return true;
    }
}