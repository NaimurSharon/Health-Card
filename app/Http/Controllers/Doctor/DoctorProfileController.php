<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\DoctorDetail;

class DoctorProfileController extends Controller
{
    /**
     * Show the doctor's profile edit form
     */
    public function edit()
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }

        // Load doctor details with fallback
        $doctor->load(['doctorDetail', 'hospital']);
        
        // Get hospitals for dropdown
        $hospitals = \App\Models\Hospital::where('status', 'active')->get();

        return view('doctor.profile.edit', compact('doctor', 'hospitals'));
    }

    /**
     * Update the doctor's profile
     */
    public function update(Request $request)
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }

        // Validation rules
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'signature' => 'nullable|mimes:png,svg,webp|max:1024',
            'specialization' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'hospital_id' => 'required|exists:hospitals,id',
            'experience' => 'required|string|max:100',
            'license_number' => 'required|string|max:100|unique:doctor_details,license_number,' . ($doctor->doctorDetail ? $doctor->doctorDetail->id : 'NULL') . ',id,user_id,' . $doctor->id,
            'consultation_fee' => 'required|numeric|min:0',
            'follow_up_fee' => 'nullable|numeric|min:0',
            'emergency_fee' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'max_patients_per_day' => 'nullable|integer|min:1|max:50',
            'languages' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
            'remove_profile_image' => 'nullable|boolean',
            'remove_signature' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $doctor) {
                // Handle profile image upload/removal
                $profileImagePath = $doctor->profile_image;
                
                if ($request->has('remove_profile_image') && $request->remove_profile_image) {
                    // Remove existing profile image
                    if ($profileImagePath) {
                        $this->deleteOldFile($profileImagePath);
                        $profileImagePath = null;
                    }
                } elseif ($request->hasFile('profile_image')) {
                    // Upload new profile image
                    if ($profileImagePath) {
                        $this->deleteOldFile($profileImagePath);
                    }
                    $profileImagePath = $this->handleImageUpload($request->file('profile_image'), 'doctor_profile');
                }

                // Handle signature image upload/removal
                $signaturePath = $doctor->signature;
                
                if ($request->has('remove_signature') && $request->remove_signature) {
                    // Remove existing signature
                    if ($signaturePath) {
                        $this->deleteOldFile($signaturePath);
                        $signaturePath = null;
                    }
                } elseif ($request->hasFile('signature')) {
                    // Upload new signature
                    if ($signaturePath) {
                        $this->deleteOldFile($signaturePath);
                    }
                    $signaturePath = $this->handleSignatureUpload($request->file('signature'));
                }

                // Update user
                $updateData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'specialization' => $request->specialization,
                    'qualifications' => $request->qualifications,
                    'hospital_id' => $request->hospital_id,
                ];

                // Only update profile image if changed
                if ($profileImagePath !== $doctor->profile_image) {
                    $updateData['profile_image'] = $profileImagePath;
                }

                if ($request->filled('password')) {
                    $updateData['password'] = Hash::make($request->password);
                }

                $doctor->update($updateData);

                // Update or create doctor details
                $doctorDetailData = [
                    'consultation_fee' => $request->consultation_fee,
                    'follow_up_fee' => $request->follow_up_fee,
                    'emergency_fee' => $request->emergency_fee,
                    'license_number' => $request->license_number,
                    'experience' => $request->experience,
                    'bio' => $request->bio,
                    'department' => $request->department,
                    'designation' => $request->designation,
                    'max_patients_per_day' => $request->max_patients_per_day ?? 20,
                ];
                
                // Only update signature if changed
                if ($signaturePath !== $doctor->doctorDetail->signature) {
                    $doctorDetailData['signature'] = $signaturePath;
                }

                // Handle languages - convert string to array
                if ($request->languages) {
                    $languages = array_map('trim', explode(',', $request->languages));
                    $doctorDetailData['languages'] = json_encode($languages);
                } else {
                    $doctorDetailData['languages'] = json_encode(['Bangla', 'English']);
                }

                if ($doctor->doctorDetail) {
                    $doctor->doctorDetail->update($doctorDetailData);
                } else {
                    DoctorDetail::create(array_merge(['user_id' => $doctor->id], $doctorDetailData));
                }
            });

            return redirect()->route('doctor.profile.edit')
                ->with('success', 'Profile updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function handleImageUpload($file, $type)
    {
        $baseName = $type . '_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
    
        $destinationPath = public_path('storage/users');
    
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        if (!file_exists($destinationPath . '/compressed')) {
            mkdir($destinationPath . '/compressed', 0755, true);
        }
    
        $originalName = $baseName . '.' . $originalExtension;
        $file->move($destinationPath, $originalName);
    
        $webpName  = $baseName . '.webp';
        $webpPath  = $destinationPath . '/compressed/' . $webpName;
    
        // Convert to WebP (GD)
        $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $webpPath, 80);
    
        // Delete original
        unlink($destinationPath . '/' . $originalName);
    
        return 'users/compressed/' . $webpName;
    }


    protected function handleSignatureUpload($file)
    {
        $baseName = 'signature_' . Str::uuid();
        $originalExtension = strtolower($file->getClientOriginalExtension());
    
        $destinationPath = public_path('storage/signatures');
    
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        if (!file_exists($destinationPath . '/compressed')) {
            mkdir($destinationPath . '/compressed', 0755, true);
        }
    
        $originalName = $baseName . '.' . $originalExtension;
        $file->move($destinationPath, $originalName);
    
        // Transparent PNG / SVG (keep original)
        if (in_array($originalExtension, ['png', 'svg'])) {
    
            $finalName = $baseName . '.' . $originalExtension;
            $finalPath = $destinationPath . '/compressed/' . $finalName;
    
            if ($originalExtension === 'png') {
                $this->optimizePngWithGD($destinationPath . '/' . $originalName, $finalPath);
            } else {
                copy($destinationPath . '/' . $originalName, $finalPath);
            }
    
        } else {
    
            // Convert to WebP
            $finalName = $baseName . '.webp';
            $finalPath = $destinationPath . '/compressed/' . $finalName;
    
            $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $finalPath, 90);
        }
    
        // Remove original
        unlink($destinationPath . '/' . $originalName);
    
        return 'signatures/compressed/' . $finalName;
    }


    /**
     * Optimize PNG while preserving transparency
     */
    protected function optimizePngWithGD($sourcePath, $destinationPath)
    {
        $image = imagecreatefrompng($sourcePath);
        
        // Preserve transparency
        imagealphablending($image, false);
        imagesavealpha($image, true);
        
        // Save optimized PNG
        imagepng($image, $destinationPath, 6); // Compression level 6 (0-9)
        imagedestroy($image);
        
        return true;
    }

    /**
     * Convert image to WebP using GD library
     */
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

    protected function deleteOldFile($path)
    {
        if (!$path) return;
    
        $filePath = public_path('storage/' . $path);
    
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    
        // Also remove uncompressed version
        $uncompressedPath = str_replace('compressed/', '', $filePath);
    
        if (file_exists($uncompressedPath)) {
            unlink($uncompressedPath);
        }
    }


    /**
     * Show the doctor's profile (view only)
     */
    public function show()
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }

        $doctor->load(['doctorDetail', 'hospital']);

        return view('doctor.profile.show', compact('doctor'));
    }
}