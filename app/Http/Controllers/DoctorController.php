<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hospital;
use App\Models\DoctorDetail;
use App\Models\DoctorAvailability;
use App\Models\DoctorLeaveDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'doctor')
            ->with(['hospital', 'doctorDetail', 'doctorAvailabilities']);

        // Regional filtering for Bangladesh
        if ($request->has('region') && $request->region) {
            $query->whereHas('hospital', function($q) use ($request) {
                $regionMap = [
                    'dhaka' => ['ঢাকা', 'Dhaka'],
                    'chittagong' => ['চট্টগ্রাম', 'Chittagong'],
                    'sylhet' => ['সিলেট', 'Sylhet'],
                    'rajshahi' => ['রাজশাহী', 'Rajshahi'],
                    'khulna' => ['খুলনা', 'Khulna'],
                    'barishal' => ['বরিশাল', 'Barishal'],
                    'rangpur' => ['রংপুর', 'Rangpur']
                ];

                if (isset($regionMap[$request->region])) {
                    $regions = $regionMap[$request->region];
                    $q->where(function($query) use ($regions) {
                        foreach ($regions as $region) {
                            $query->orWhere('address', 'like', '%' . $region . '%');
                        }
                    });
                }
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('hospital_id') && $request->hospital_id) {
            $query->where('hospital_id', $request->hospital_id);
        }

        if ($request->has('specialization') && $request->specialization) {
            $query->where('specialization', 'like', '%' . $request->specialization . '%');
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('specialization', 'like', '%' . $request->search . '%');
            });
        }

        $doctors = $query->orderBy('name')->paginate(20);
        $hospitals = Hospital::active()->get();

        // Bangladeshi regions for filter
        $regions = [
            'dhaka' => 'Dhaka Division',
            'chittagong' => 'Chittagong Division', 
            'sylhet' => 'Sylhet Division',
            'rajshahi' => 'Rajshahi Division',
            'khulna' => 'Khulna Division',
            'barishal' => 'Barishal Division',
            'rangpur' => 'Rangpur Division'
        ];

        return view('backend.doctors.index', compact('doctors', 'hospitals', 'regions'));
    }

    public function create()
    {
        $hospitals = Hospital::active()->get();
        $daysOfWeek = [
            'sunday' => 'Sunday',
            'monday' => 'Monday', 
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        ];
        
        // Common Bangladeshi specializations
        $specializations = [
            'Pediatrics',
            'General Medicine', 
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Gynecology',
            'Dermatology',
            'Psychiatry',
            'Dentistry',
            'Nursing',
            'Physiotherapy'
        ];

        return view('backend.doctors.form', compact('hospitals', 'daysOfWeek', 'specializations'));
    }

    public function show(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $doctor->load(['hospital', 'doctorDetail', 'doctorAvailabilities', 'doctorLeaveDates']);
        return view('backend.doctors.show', compact('doctor'));
    }
    
    public function view(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $doctor->load(['hospital', 'doctorDetail', 'doctorAvailabilities', 'doctorLeaveDates']);
        return view('frontend.doctors.view', compact('doctor'));
    }

    public function edit(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }
    
        $hospitals = Hospital::active()->get();
        $daysOfWeek = [
            'sunday' => 'Sunday',
            'monday' => 'Monday', 
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        ];
        
        // Common Bangladeshi specializations
        $specializations = [
            'Pediatrics',
            'General Medicine', 
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Gynecology',
            'Dermatology',
            'Psychiatry',
            'Dentistry',
            'Nursing',
            'Physiotherapy'
        ];
    
        $doctor->load('doctorDetail', 'doctorAvailabilities');
        
        return view('backend.doctors.form', compact('doctor', 'hospitals', 'daysOfWeek', 'specializations'));
    }
    
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Custom validation for availabilities
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => 'required|string|max:20|regex:/^[0-9+]{11,15}$/',
                'address' => 'required|string',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'required|in:male,female,other',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'specialization' => 'required|string|max:255',
                'qualifications' => 'required|string',
                'hospital_id' => 'required|exists:hospitals,id',
                'experience' => 'required|string|max:100',
                'license_number' => 'required|string|max:100|unique:doctor_details',
                'consultation_fee' => 'required|numeric|min:0',
                'follow_up_fee' => 'nullable|numeric|min:0',
                'emergency_fee' => 'nullable|numeric|min:0',
                'bio' => 'nullable|string',
                'department' => 'nullable|string|max:255',
                'designation' => 'nullable|string|max:255',
                'max_patients_per_day' => 'nullable|integer|min:1|max:50',
                'languages' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            // Manually validate availabilities
            $hasValidAvailability = false;
            if ($request->has('availabilities')) {
                foreach ($request->availabilities as $day => $availability) {
                    if (isset($availability['enabled']) && $availability['enabled'] == '1') {
                        if (empty($availability['start_time']) || empty($availability['end_time'])) {
                            $validator->errors()->add("availabilities.{$day}.start_time", "Start time and end time are required for {$day}.");
                        } else {
                            $hasValidAvailability = true;
                        }
                    }
                }
            }

            if (!$hasValidAvailability) {
                $validator->errors()->add('availabilities', 'At least one day must be available with valid time slots.');
            }

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            // Handle profile image upload
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $this->handleImageUpload($request->file('profile_image'), 'doctor_profile');
            }

            // Create user
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'role' => 'doctor',
                'specialization' => $request->specialization,
                'qualifications' => $request->qualifications,
                'hospital_id' => $request->hospital_id,
                'status' => $request->status,
            ];

            if ($profileImagePath) {
                $userData['profile_image'] = $profileImagePath;
            }

            $user = User::create($userData);

            // Create doctor details
            $doctorDetail = DoctorDetail::create([
                'user_id' => $user->id,
                'consultation_fee' => $request->consultation_fee,
                'follow_up_fee' => $request->follow_up_fee,
                'emergency_fee' => $request->emergency_fee,
                'license_number' => $request->license_number,
                'experience' => $request->experience,
                'bio' => $request->bio,
                'specializations' => $request->specializations ? json_decode($request->specializations) : [$request->specialization],
                'languages' => $request->languages ? array_map('trim', explode(',', $request->languages)) : ['Bangla', 'English'],
                'department' => $request->department,
                'designation' => $request->designation,
                'max_patients_per_day' => $request->max_patients_per_day ?? 20,
                'is_available' => true,
            ]);

            // Create availabilities
            if ($request->has('availabilities')) {
                foreach ($request->availabilities as $day => $availability) {
                    if (isset($availability['enabled']) && $availability['enabled'] == '1' && 
                        !empty($availability['start_time']) && !empty($availability['end_time'])) {
                        DoctorAvailability::create([
                            'doctor_id' => $user->id,
                            'day_of_week' => $day,
                            'start_time' => $availability['start_time'],
                            'end_time' => $availability['end_time'],
                            'slot_duration' => $availability['slot_duration'] ?? 30,
                            'max_appointments' => $availability['max_appointments'] ?? 10,
                            'is_available' => true,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function update(Request $request, User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        DB::transaction(function () use ($request, $doctor) {
            // Custom validation for availabilities
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'date_of_birth' => 'nullable|date',
                'gender' => 'required|in:male,female,other',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'specialization' => 'required|string|max:255',
                'qualifications' => 'required|string',
                'hospital_id' => 'required|exists:hospitals,id',
                'experience' => 'required|string|max:100',
                'license_number' => 'required|string|max:100|unique:doctor_details,license_number,' . ($doctor->doctorDetail ? $doctor->doctorDetail->id : 'NULL'),
                'consultation_fee' => 'required|numeric|min:0',
                'follow_up_fee' => 'nullable|numeric|min:0',
                'emergency_fee' => 'nullable|numeric|min:0',
                'bio' => 'nullable|string',
                'department' => 'nullable|string|max:255',
                'designation' => 'nullable|string|max:255',
                'max_patients_per_day' => 'nullable|integer|min:1|max:50',
                'languages' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'password' => 'nullable|min:8|confirmed',
                'remove_profile_image' => 'nullable|boolean',
            ]);

            // Manually validate availabilities
            $hasValidAvailability = false;
            if ($request->has('availabilities')) {
                foreach ($request->availabilities as $day => $availability) {
                    if (isset($availability['enabled']) && $availability['enabled'] == '1') {
                        if (empty($availability['start_time']) || empty($availability['end_time'])) {
                            $validator->errors()->add("availabilities.{$day}.start_time", "Start time and end time are required for {$day}.");
                        } else {
                            $hasValidAvailability = true;
                        }
                    }
                }
            }

            if (!$hasValidAvailability) {
                $validator->errors()->add('availabilities', 'At least one day must be available with valid time slots.');
            }

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

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
                'status' => $request->status,
            ];

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
                'specializations' => $request->specializations ? json_decode($request->specializations) : [$request->specialization],
                'languages' => $request->languages ? array_map('trim', explode(',', $request->languages)) : ['Bangla', 'English'],
                'department' => $request->department,
                'designation' => $request->designation,
                'max_patients_per_day' => $request->max_patients_per_day ?? 20,
            ];

            if ($doctor->doctorDetail) {
                $doctor->doctorDetail->update($doctorDetailData);
            } else {
                DoctorDetail::create(array_merge(['user_id' => $doctor->id], $doctorDetailData));
            }

            // Update availabilities - only create for enabled days
            $doctor->doctorAvailabilities()->delete();
            
            if ($request->has('availabilities')) {
                foreach ($request->availabilities as $day => $availability) {
                    // Only create availability if the day is enabled and has valid times
                    if (isset($availability['enabled']) && $availability['enabled'] == '1' && 
                        !empty($availability['start_time']) && !empty($availability['end_time'])) {
                        DoctorAvailability::create([
                            'doctor_id' => $doctor->id,
                            'day_of_week' => $day,
                            'start_time' => $availability['start_time'],
                            'end_time' => $availability['end_time'],
                            'slot_duration' => $availability['slot_duration'] ?? 30,
                            'max_appointments' => $availability['max_appointments'] ?? 10,
                            'is_available' => true,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        DB::transaction(function () use ($doctor) {
            $doctor->doctorDetail()->delete();
            $doctor->doctorAvailabilities()->delete();
            $doctor->doctorLeaveDates()->delete();
            $doctor->delete();
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }

    public function leaveDates(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }
    
        $leaveDates = $doctor->doctorLeaveDates()
            ->orderBy('leave_date', 'desc')
            ->paginate(20); // 👈 Add pagination (change number as needed)
    
        return view('backend.doctors.leave-dates', compact('doctor', 'leaveDates'));
    }


    public function storeLeaveDate(Request $request, User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $request->validate([
            'leave_date' => 'required|date|after:yesterday',
            'reason' => 'required|string|max:255',
            'is_full_day' => 'required|boolean',
            'start_time' => 'required_if:is_full_day,0|date_format:H:i|nullable',
            'end_time' => 'required_if:is_full_day,0|date_format:H:i|after:start_time|nullable',
        ]);

        DoctorLeaveDate::create([
            'doctor_id' => $doctor->id,
            'leave_date' => $request->leave_date,
            'reason' => $request->reason,
            'is_full_day' => $request->is_full_day,
            'start_time' => $request->is_full_day ? null : $request->start_time,
            'end_time' => $request->is_full_day ? null : $request->end_time,
        ]);

        return redirect()->route('admin.doctors.leave-dates', $doctor)
            ->with('success', 'Leave date added successfully.');
    }

    public function destroyLeaveDate(User $doctor, DoctorLeaveDate $leaveDate)
    {
        
        if ($leaveDate->doctor_id != $doctor->id) {
            abort(404);
        }

        $leaveDate->delete();

        return redirect()->route('admin.doctors.leave-dates', $doctor)
            ->with('success', 'Leave date deleted successfully.');
    }

    // Toggle availability
    public function toggleAvailability(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        if ($doctor->doctorDetail) {
            $doctor->doctorDetail->update([
                'is_available' => !$doctor->doctorDetail->is_available
            ]);
        }

        return redirect()->back()
            ->with('success', 'Availability updated successfully.');
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
}