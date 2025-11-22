<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WebsiteSettingsController extends Controller
{
    public function index()
    {
        // Get all settings grouped by section
        $heroSettings = WebsiteSetting::getSection('hero');
        $ministerSettings = WebsiteSetting::getSection('ministers');
        $generalSettings = WebsiteSetting::getSection('general');

        return view('backend.website-settings.index', compact('heroSettings', 'ministerSettings', 'generalSettings'));
    }

    public function update(Request $request)
    {
        // Debug: Check what's coming in the request
        // dd($request->all());

        $validated = $request->validate([
            // Hero Section
            'youtube_playlist_id' => 'nullable|string|max:100',
            'youtube_playlist_url' => 'nullable|url|max:255',
            'youtube_auto_play' => 'nullable|boolean',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:1000',
            'cta_button_text' => 'nullable|string|max:50',
            'cta_button_link' => 'nullable|string|max:255',
            
            // Ministers Section
            'ministers_list' => 'nullable|array',
            'ministers_list.*.name' => 'required|string|max:255',
            'ministers_list.*.ministry' => 'required|string|max:255',
            'ministers_list.*.image_link' => 'nullable|string|max:500',
            'ministers_list.*.display_order' => 'nullable|integer',
            'display_count' => 'nullable|integer|min:1|max:20',
            'section_title' => 'nullable|string|max:255',

            // Minister Images - FIXED: Make it optional array
            'minister_images' => 'sometimes|array',
            'minister_images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

            // General Settings
            // 'site_title' => 'required|string|max:255',
            // 'site_description' => 'nullable|string|max:500',
            // 'contact_email' => 'nullable|email|max:255',
            // 'phone_number' => 'nullable|string|max:20',
            // 'site_logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            // 'site_favicon' => 'sometimes|image|mimes:jpeg,png,jpg,gif,ico,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->id();

            // Handle Hero Section Settings
            $this->updateHeroSettings($request, $userId);

            // Handle Ministers Section Settings
            $this->updateMinistersSettings($request, $userId);

            // Handle General Settings
            $this->updateGeneralSettings($request, $userId);

            DB::commit();

            return redirect()->route('admin.website-settings.index')
                ->with('success', 'Website settings updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    private function updateHeroSettings(Request $request, $userId)
    {
        $heroSettings = [
            'youtube_playlist_id' => ['value' => $request->youtube_playlist_id, 'type' => 'string'],
            'youtube_playlist_url' => ['value' => $request->youtube_playlist_url, 'type' => 'string'],
            'youtube_auto_play' => ['value' => $request->boolean('youtube_auto_play'), 'type' => 'boolean'],
            'hero_title' => ['value' => $request->hero_title, 'type' => 'string'],
            'hero_subtitle' => ['value' => $request->hero_subtitle, 'type' => 'string'],
            'cta_button_text' => ['value' => $request->cta_button_text, 'type' => 'string'],
            'cta_button_link' => ['value' => $request->cta_button_link, 'type' => 'string'],
        ];

        foreach ($heroSettings as $key => $setting) {
            if ($setting['value'] !== null) {
                WebsiteSetting::setValue(
                    'hero',
                    $key,
                    $setting['value'],
                    $setting['type'],
                    "Hero section {$key}",
                    $userId
                );
            }
        }
    }

    private function updateMinistersSettings(Request $request, $userId)
    {
        // Get existing ministers data to preserve images
        $existingMinisters = WebsiteSetting::getValue('ministers', 'ministers_list', []);
        $ministersData = $request->ministers_list ?? [];
        
        // Handle minister images upload
        if ($request->hasFile('minister_images')) {
            foreach ($request->file('minister_images') as $index => $file) {
                if ($file && isset($ministersData[$index])) {
                    $imagePath = $this->handleImageUpload($file, 'ministers');
                    
                    // Delete old image if exists
                    if (isset($existingMinisters[$index]['image_link']) && !empty($existingMinisters[$index]['image_link'])) {
                        $this->deleteOldFile($existingMinisters[$index]['image_link']);
                    }
                    
                    $ministersData[$index]['image_link'] = $imagePath;
                }
            }
        }

        // Preserve existing images for ministers without new uploads
        foreach ($ministersData as $index => &$minister) {
            if (empty($minister['image_link']) && isset($existingMinisters[$index]['image_link'])) {
                $minister['image_link'] = $existingMinisters[$index]['image_link'];
            }
        }

        // Update ministers list
        if (!empty($ministersData)) {
            WebsiteSetting::setValue(
                'ministers',
                'ministers_list',
                $ministersData,
                'json',
                'List of ministers with details',
                $userId
            );
        }

        // Update other minister settings
        $ministerSettings = [
            'display_count' => ['value' => $request->display_count, 'type' => 'integer'],
            'section_title' => ['value' => $request->section_title, 'type' => 'string'],
        ];

        foreach ($ministerSettings as $key => $setting) {
            if ($setting['value'] !== null) {
                WebsiteSetting::setValue(
                    'ministers',
                    $key,
                    $setting['value'],
                    $setting['type'],
                    "Ministers section {$key}",
                    $userId
                );
            }
        }
    }

    private function updateGeneralSettings(Request $request, $userId)
    {
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $logoPath = $this->handleImageUpload($request->file('site_logo'), 'logo');
            $this->deleteOldFile(WebsiteSetting::getValue('general', 'site_logo'));
            WebsiteSetting::setValue('general', 'site_logo', $logoPath, 'string', 'Site logo image', $userId);
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $faviconPath = $this->handleImageUpload($request->file('site_favicon'), 'favicon');
            $this->deleteOldFile(WebsiteSetting::getValue('general', 'site_favicon'));
            WebsiteSetting::setValue('general', 'site_favicon', $faviconPath, 'string', 'Site favicon', $userId);
        }

        // Update other general settings
        $generalSettings = [
            'site_title' => ['value' => $request->site_title, 'type' => 'string'],
            'site_description' => ['value' => $request->site_description, 'type' => 'string'],
            'contact_email' => ['value' => $request->contact_email, 'type' => 'string'],
            'phone_number' => ['value' => $request->phone_number, 'type' => 'string'],
        ];

        foreach ($generalSettings as $key => $setting) {
            if ($setting['value'] !== null) {
                WebsiteSetting::setValue(
                    'general',
                    $key,
                    $setting['value'],
                    $setting['type'],
                    "General setting {$key}",
                    $userId
                );
            }
        }
    }

    protected function handleImageUpload($file, $type)
    {
        $baseName = $type . '_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/website-settings');
        
        // Create directories if they don't exist
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
        
        // Convert to WebP using GD
        $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $webpPath, $type === 'favicon' ? 80 : 75);
    
        // Delete original file
        if (file_exists($destinationPath . '/' . $originalName)) {
            unlink($destinationPath . '/' . $originalName);
        }
    
        return 'website-settings/compressed/' . $webpName;
    }

    protected function convertToWebpWithGD($sourcePath, $destinationPath, $quality = 75)
    {
        if (!file_exists($sourcePath)) {
            throw new \Exception("Source file does not exist: $sourcePath");
        }

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($sourcePath);
                if (!$image) {
                    throw new \Exception("Failed to create image from JPEG: $sourcePath");
                }
                break;
            case 'png':
                $image = imagecreatefrompng($sourcePath);
                if (!$image) {
                    throw new \Exception("Failed to create image from PNG: $sourcePath");
                }
                // Preserve transparency for PNG
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'gif':
                $image = imagecreatefromgif($sourcePath);
                if (!$image) {
                    throw new \Exception("Failed to create image from GIF: $sourcePath");
                }
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
            throw new \Exception("Failed to convert image to WebP: $destinationPath");
        }
        
        return true;
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

    /**
     * Remove a minister from the list
     */
    public function removeMinister(Request $request)
    {
        try {
            $index = $request->index;
            $ministers = WebsiteSetting::getValue('ministers', 'ministers_list', []);

            if (isset($ministers[$index])) {
                // Delete the minister's image if exists
                if (!empty($ministers[$index]['image_link'])) {
                    $this->deleteOldFile($ministers[$index]['image_link']);
                }

                array_splice($ministers, $index, 1);

                WebsiteSetting::setValue('ministers', 'ministers_list', $ministers, 'json', 'Updated ministers list', auth()->id());

                return response()->json(['success' => true, 'message' => 'Minister removed successfully']);
            }

            return response()->json(['success' => false, 'message' => 'Minister not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}