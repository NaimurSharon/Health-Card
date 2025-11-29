<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        $hospitals = $query->orderBy('name')->paginate(20);
        return view('backend.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('backend.hospitals.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,private,specialized,clinic',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:hospitals',
            'emergency_contact' => 'required|string|max:20',
            'website' => 'nullable|url',
            'youtube_video_url' => 'nullable|url',
            'services' => 'nullable|string',
            'facilities' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert services string to array if needed
        if ($request->has('services') && is_string($request->services)) {
            $validated['services'] = array_map('trim', explode(',', $request->services));
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $this->handleImageUpload($image, 'hospital');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        Hospital::create($validated);

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital created successfully.');
    }

    public function show(Hospital $hospital)
    {
        $hospital->loadCount('doctors');
        return view('backend.hospitals.show', compact('hospital'));
    }
    
    public function view(Hospital $hospital)
    {
        $hospital->loadCount('doctors');
        $hospital->load(['doctors' => function($query) {
            $query->where('status', 'active')
                  ->where('role', 'doctor');
        }]);
        
        return view('frontend.hospitals.view', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        return view('backend.hospitals.form', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,private,specialized,clinic',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:hospitals,email,' . $hospital->id,
            'emergency_contact' => 'required|string|max:20',
            'website' => 'nullable|url',
            'youtube_video_url' => 'nullable|url',
            'services' => 'nullable|string',
            'facilities' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert services string to array if needed
        if ($request->has('services') && is_string($request->services)) {
            $validated['services'] = array_map('trim', explode(',', $request->services));
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = $hospital->images ?? [];
            foreach ($request->file('images') as $image) {
                $path = $this->handleImageUpload($image, 'hospital');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        // Handle image deletion
        if ($request->has('delete_images')) {
            $imagesToKeep = array_diff($hospital->images ?? [], $request->delete_images);
            
            // Delete files from storage
            foreach ($request->delete_images as $imageToDelete) {
                $this->deleteOldFile($imageToDelete);
            }
            
            $validated['images'] = array_values($imagesToKeep);
        }

        $hospital->update($validated);

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital updated successfully.');
    }

    public function destroy(Hospital $hospital)
    {
        if ($hospital->doctors()->count() > 0) {
            return redirect()->route('admin.hospitals.index')
                ->with('error', 'Cannot delete hospital that has doctors. Please transfer doctors first.');
        }

        // Delete associated images
        if ($hospital->images) {
            foreach ($hospital->images as $image) {
                $this->deleteOldFile($image);
            }
        }

        $hospital->delete();

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital deleted successfully.');
    }

    // Remove single image
    public function removeImage(Hospital $hospital, $imageIndex)
    {
        $images = $hospital->images ?? [];
        
        if (isset($images[$imageIndex])) {
            $imageToDelete = $images[$imageIndex];
            
            // Delete file from storage
            $this->deleteOldFile($imageToDelete);
            
            // Remove from array
            unset($images[$imageIndex]);
            $hospital->update(['images' => array_values($images)]);
            
            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
        }
        
        return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
    }

    /**
     * Handle image upload with WebP conversion
     */
    protected function handleImageUpload($file, $type)
    {
        $baseName = $type . '_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/hospitals');
        
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
        $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $webpPath, 60);
    
        // Delete original file
        unlink($destinationPath . '/' . $originalName);
    
        return 'hospitals/compressed/' . $webpName;
    }

    /**
     * Delete old file from storage
     */
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
     * Convert image to WebP using GD library
     */
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
                // Preserve transparency for PNG
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
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