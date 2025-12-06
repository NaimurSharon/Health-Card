<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PrincipalSchoolController extends Controller
{
    public function editSchool()
    {
        $principal = auth()->user();
        $school = $principal->school;

        if (!$school) {
            return redirect()->route('principal.dashboard')
                ->with('error', 'No school assigned to you.');
        }

        return view('principal.school.edit', compact('school'));
    }

    public function updateSchool(Request $request)
    {
        $principal = auth()->user();
        $school = $principal->school;

        if (!$school) {
            return redirect()->back()->with('error', 'No school assigned to you.');
        }

        // Basic validation
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('schools', 'code')->ignore($school->id)
            ],
            'type' => 'required|in:government,private,madrasa,international',
            'established_year' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Prepare data
            $data = $request->only([
                'name',
                'code',
                'type',
                'established_year',
                'address',
                'city',
                'phone',
                'email',
                'website',
                'total_students',
                'total_teachers',
                'status'
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $this->handleImageUpload($request->file('logo'), 'school_logo');
                $this->deleteOldFile($school->logo);
                $data['logo'] = $logoPath;
            }

            // Update the school
            $school->update($data);

            return redirect()->route('principal.school.edit')
                ->with('success', 'School information updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating school: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updatePrincipalInfo(Request $request)
    {
        $principal = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($principal->id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            $data = $request->only(['name', 'email', 'phone', 'address']);

            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $this->handleImageUpload($request->file('profile_photo'), 'profile_photo');
                $this->deleteOldFile($principal->profile_photo);
                $data['profile_image'] = $profilePhotoPath;
            }

            $principal->update($data);

            return redirect()->route('principal.school.edit')
                ->with('success', 'Principal information updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating principal information: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function handleImageUpload($file, $type)
    {
        $baseName = $type . '_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/' . ($type === 'school_logo' ? 'schools' : 'profile-photos'));

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
        $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $webpPath, 80);

        // Delete original file
        unlink($destinationPath . '/' . $originalName);

        // Return storage path (relative to storage folder)
        $storagePath = ($type === 'school_logo' ? 'schools' : 'profile-photos') . '/compressed/' . $webpName;
        return $storagePath;
    }

    protected function deleteOldFile($path)
    {
        if (!$path)
            return;

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

    protected function convertToWebpWithGD($sourcePath, $destinationPath, $quality = 80)
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