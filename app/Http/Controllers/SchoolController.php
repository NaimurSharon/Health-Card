<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = School::withCount(['users']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('principal_name', 'like', "%{$search}%");
            });
        }

        $schools = $query->orderBy('name')->paginate(20);
        
        return view('backend.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('backend.schools.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:schools',
            'code' => 'required|string|max:50|unique:schools',
            'type' => 'required|in:government,private,madrasa,international',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools',
            'website' => 'nullable|url',
            'principal_name' => 'required|string|max:255',
            'principal_phone' => 'nullable|string|max:20',
            'principal_email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'school_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'motto' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'academic_system' => 'nullable|in:national,cambridge,ib,other',
            'medium' => 'nullable|in:bangla,english,both',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'total_staff' => 'nullable|integer|min:0',
            'campus_area' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|string',
            'accreditations' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        // Handle facilities and accreditations (convert comma-separated to JSON array)
        if ($request->has('facilities') && $request->facilities) {
            $facilities = array_map('trim', explode(',', $request->facilities));
            $data['facilities'] = json_encode($facilities);
        }

        if ($request->has('accreditations') && $request->accreditations) {
            $accreditations = array_map('trim', explode(',', $request->accreditations));
            $data['accreditations'] = json_encode($accreditations);
        }

        // Handle file uploads with WebP conversion
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->handleImageUpload($request->file('logo'), 'logo');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->handleImageUpload($request->file('cover_image'), 'cover');
        }

        if ($request->hasFile('school_image')) {
            $data['school_image'] = $this->handleImageUpload($request->file('school_image'), 'school');
        }

        School::create($data);

        return redirect()->route('admin.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function show(School $school)
    {
        $school->loadCount(['users']);
        $school->load('users'); // Load users for role-based counting
        return view('backend.schools.show', compact('school'));
    }

    public function edit(School $school)
    {
        return view('backend.schools.form', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:schools,name,' . $school->id,
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
            'type' => 'required|in:government,private,madrasa,international',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'website' => 'nullable|url',
            'principal_name' => 'required|string|max:255',
            'principal_phone' => 'nullable|string|max:20',
            'principal_email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jjpg,gif,webp|max:5120',
            'school_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'motto' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'academic_system' => 'nullable|in:national,cambridge,ib,other',
            'medium' => 'nullable|in:bangla,english,both',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'total_staff' => 'nullable|integer|min:0',
            'campus_area' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|string',
            'accreditations' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        // Handle facilities and accreditations (convert comma-separated to JSON array)
        if ($request->has('facilities') && $request->facilities) {
            $facilities = array_map('trim', explode(',', $request->facilities));
            $data['facilities'] = json_encode($facilities);
        } else {
            $data['facilities'] = null;
        }

        if ($request->has('accreditations') && $request->accreditations) {
            $accreditations = array_map('trim', explode(',', $request->accreditations));
            $data['accreditations'] = json_encode($accreditations);
        } else {
            $data['accreditations'] = null;
        }

        // Handle file uploads with WebP conversion
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($school->logo) {
                $this->deleteOldFile($school->logo);
            }
            $data['logo'] = $this->handleImageUpload($request->file('logo'), 'logo');
        }

        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($school->cover_image) {
                $this->deleteOldFile($school->cover_image);
            }
            $data['cover_image'] = $this->handleImageUpload($request->file('cover_image'), 'cover');
        }

        if ($request->hasFile('school_image')) {
            // Delete old school image if exists
            if ($school->school_image) {
                $this->deleteOldFile($school->school_image);
            }
            $data['school_image'] = $this->handleImageUpload($request->file('school_image'), 'school');
        }

        $school->update($data);

        return redirect()->route('admin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        if ($school->users()->count() > 0) {
            return redirect()->route('admin.schools.index')
                ->with('error', 'Cannot delete school that has users. Please transfer users first.');
        }

        // Delete associated files
        if ($school->logo) {
            $this->deleteOldFile($school->logo);
        }
        if ($school->cover_image) {
            $this->deleteOldFile($school->cover_image);
        }
        if ($school->school_image) {
            $this->deleteOldFile($school->school_image);
        }

        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    // Image Upload Handlers
    protected function handleImageUpload($file, $type)
    {
        $baseName = 'school_' . $type . '_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/schools');
        
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
        $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $webpPath, $this->getQualityForType($type));
    
        // Delete original file
        unlink($destinationPath . '/' . $originalName);
    
        return 'schools/compressed/' . $webpName;
    }

    protected function getQualityForType($type)
    {
        return match($type) {
            'logo' => 80,    // Higher quality for logos
            'cover' => 75,   // Medium quality for cover images
            'school' => 70,  // Good quality for school images
            default => 75    // Default quality
        };
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

    protected function convertToWebpWithGD($sourcePath, $destinationPath, $quality = 75)
    {
        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($sourcePath);
                // Preserve transparency for JPEG (though JPEG doesn't support transparency)
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
                // Preserve transparency for GIF
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
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

    // Special handler for watermark to preserve transparency (if needed in future)
    protected function handleWatermarkUpload($file)
    {
        $baseName = 'watermark_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/schools');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $fileName = $baseName . '.' . $originalExtension;
        $file->move($destinationPath, $fileName);
        
        return 'schools/' . $fileName;
    }
}