<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\DoctorDetail;
use App\Models\Hospital;

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

        $doctor->load(['doctorDetail', 'hospital']);
        $hospitals = Hospital::where('status', 'active')->get();

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

        // Simplified validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $doctor->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'specialization' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'hospital_id' => 'required|exists:hospitals,id',
            'license_number' => 'required|string|max:100',
            'consultation_fee' => 'required|numeric|min:0',
            'follow_up_fee' => 'nullable|numeric|min:0',
            'emergency_fee' => 'nullable|numeric|min:0',
            'experience' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            DB::transaction(function () use ($request, $doctor) {
                // Handle profile image upload
                $profileImagePath = $doctor->profile_image;

                if ($request->hasFile('profile_image')) {
                    // Delete old profile image if exists
                    if ($profileImagePath) {
                        Storage::delete('public/' . $profileImagePath);
                    }

                    // Upload new profile image
                    $profileImagePath = $request->file('profile_image')->store('doctors/profile-images', 'public');
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
                ];

                // Check if license number is unique (excluding current doctor)
                if ($request->license_number !== $doctor->doctorDetail?->license_number) {
                    $exists = DoctorDetail::where('license_number', $request->license_number)
                        ->where('user_id', '!=', $doctor->id)
                        ->exists();

                    if ($exists) {
                        throw new \Exception('License number already exists for another doctor.');
                    }
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

    /**
     * Remove profile image
     */
    public function removeProfileImage()
    {
        $doctor = Auth::user();

        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }

        try {
            if ($doctor->profile_image) {
                Storage::delete('public/' . $doctor->profile_image);
                $doctor->update(['profile_image' => null]);
            }

            return redirect()->route('doctor.profile.edit')
                ->with('success', 'Profile image removed successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to remove profile image: ' . $e->getMessage());
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