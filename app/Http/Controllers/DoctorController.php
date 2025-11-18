<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'doctor')
            ->with('hospital');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('hospital_id') && $request->hospital_id) {
            $query->where('hospital_id', $request->hospital_id);
        }

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('specialization', 'like', '%' . $request->search . '%');
        }

        $doctors = $query->orderBy('name')->paginate(20);
        $hospitals = Hospital::active()->get();

        return view('backend.doctors.index', compact('doctors', 'hospitals'));
    }

    public function create()
    {
        $hospitals = Hospital::active()->get();
        return view('backend.doctors.form', compact('hospitals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'specialization' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'hospital_id' => 'nullable|exists:hospitals,id',
            'experience' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
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
            'experience' => $request->experience,
            'license_number' => $request->license_number,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function show(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $doctor->load('hospital');
        return view('backend.doctors.show', compact('doctor'));
    }

    public function edit(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $hospitals = Hospital::active()->get();
        return view('backend.doctors.form', compact('doctor', 'hospitals'));
    }

    public function update(Request $request, User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'specialization' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'hospital_id' => 'nullable|exists:hospitals,id',
            'experience' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

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
            'experience' => $request->experience,
            'license_number' => $request->license_number,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $doctor->update($updateData);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}