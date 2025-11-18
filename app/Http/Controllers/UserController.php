<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('school');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $users = $query->latest()->paginate(20);
        $schools = School::where('status', 'active')->get();

        return view('backend.users.index', compact('users', 'schools'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $schools = School::where('status', 'active')->get();
        return view('backend.users.form', compact('schools'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'role' => ['required', 'in:admin,teacher,student,doctor'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'qualifications' => ['nullable', 'string'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'role' => $validated['role'],
            'specialization' => $validated['specialization'],
            'qualifications' => $validated['qualifications'],
            'school_id' => $validated['school_id'],
            'status' => $validated['status'],
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $userData['profile_image'] = $this->handleImageUpload($request->file('profile_image'), 'profile');
        }

        $user = User::create($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('school');
        
        // Get statistics based on user role
        $statistics = $this->getUserStatistics($user);
        
        return view('backend.users.show', compact('user', 'statistics'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $schools = School::where('status', 'active')->get();
        return view('backend.users.form', compact('user', 'schools'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'role' => ['required', 'in:admin,teacher,student,doctor'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'qualifications' => ['nullable', 'string'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'role' => $validated['role'],
            'specialization' => $validated['specialization'],
            'qualifications' => $validated['qualifications'],
            'school_id' => $validated['school_id'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($user->profile_image) {
                $this->deleteOldFile($user->profile_image);
            }
            $updateData['profile_image'] = $this->handleImageUpload($request->file('profile_image'), 'profile');
        }

        // Handle profile image removal
        if ($request->has('remove_profile_image') && $request->remove_profile_image == '1') {
            if ($user->profile_image) {
                $this->deleteOldFile($user->profile_image);
            }
            $updateData['profile_image'] = null;
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Check if user has any related records
        if ($user->role === 'teacher' && $user->teacherSections()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete teacher with assigned sections.');
        }

        if ($user->role === 'student' && $user->medicalRecords()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete student with medical records.');
        }

        // Delete profile image if exists
        if ($user->profile_image) {
            $this->deleteOldFile($user->profile_image);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Get user statistics based on role
     */
    private function getUserStatistics(User $user)
    {
        $statistics = [];

        switch ($user->role) {
            case 'teacher':
                $statistics = [
                    'sections_count' => $user->teacherSections()->count(),
                    'subjects_count' => $user->teacherSubjects()->count(),
                    'exams_created' => $user->createdExams()->count(),
                ];
                break;
            
            case 'student':
                $statistics = [
                    'medical_records_count' => $user->medicalRecords()->count(),
                    'exam_attempts_count' => $user->examAttempts()->count(),
                    'health_card' => $user->healthCard ? 'Active' : 'None',
                ];
                break;
            
            case 'doctor':
                $statistics = [
                    'medical_records_count' => $user->recordedMedicalRecords()->count(),
                    'health_tips_count' => $user->publishedHealthTips()->count(),
                ];
                break;
            
            default:
                $statistics = [
                    'notices_published' => $user->publishedNotices()->count(),
                ];
                break;
        }

        return $statistics;
    }

    /**
     * Handle image upload and conversion to WebP
     */
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
        
        $webpName = $baseName . '.webp';
        $webpPath = $destinationPath . '/compressed/' . $webpName;
        
        // Always use GD instead of Spatie/Image to avoid Imagick dependency
        $this->convertToWebpWithGD($destinationPath . '/' . $originalName, $webpPath, 80);
    
        // Delete original file
        unlink($destinationPath . '/' . $originalName);
    
        return 'users/compressed/' . $webpName;
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
     * Convert image to WebP using GD
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
}