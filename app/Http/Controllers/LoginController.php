<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display the student login view.
     */
    public function create()
    {
        return view('auth.student-login');
    }

    /**
     * Handle an incoming student authentication request.
     */
    public function store(Request $request)
    {
        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return $this->handleAjaxLogin($request);
        }

        // Handle regular form submission
        $request->validate([
            'phone' => 'required|string|max:11',
            'student_id' => 'nullable|string',
        ]);

        $user = $this->findAndValidateUser($request);

        // Check if user role is allowed to login here
        if (!$this->isAllowedRole($user->role)) {
            throw ValidationException::withMessages([
                'phone' => __('This login is only for students and public users. Please use the regular login system.'),
            ]);
        }

        // Additional verification for students
        if ($user->role === 'student' && $request->filled('student_id')) {
            $this->validateStudentId($user->id, $request->student_id);
        }

        // Log in the user
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    /**
     * Handle AJAX login requests
     */
    private function handleAjaxLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:11',
            'student_id' => 'nullable|string',
        ]);

        $user = $this->findAndValidateUser($request);

        // Check if user role is allowed to login here
        if (!$this->isAllowedRole($user->role)) {
            return response()->json([
                'success' => false,
                'message' => 'This login is only for students and public users. Please use the regular login system.'
            ], 422);
        }

        // Additional verification for students
        if ($user->role === 'student' && $request->filled('student_id')) {
            $student = Student::where('student_id', $request->student_id)
                             ->where('user_id', $user->id)
                             ->first();
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided student ID does not match.'
                ], 422);
            }
        }

        // Log in the user
        Auth::login($user, $request->boolean('remember'));

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'redirect' => route('home')
        ]);
    }

    /**
     * Find and validate user by phone number
     */
    private function findAndValidateUser(Request $request)
    {
        // Clean the phone number (remove any formatting)
        $phone = preg_replace('/\D/', '', $request->phone);
        
        // Find user by phone (for both students and public users)
        $user = User::where('phone', $phone)
                   ->where('status', 'active')
                   ->first();

        if (!$user) {
            // If no user found, check if it's a student
            $student = Student::where('emergency_contact', $phone)
                             ->where('status', 'active')
                             ->first();

            if ($student && $student->user) {
                $user = $student->user;
            }
        }

        if (!$user) {
            throw ValidationException::withMessages([
                'phone' => __('The provided credentials do not match our records.'),
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'phone' => __('Your account is not active. Please contact administration.'),
            ]);
        }

        return $user;
    }

    /**
     * Check if user role is allowed to use this login method
     */
    private function isAllowedRole($role)
    {
        return in_array($role, ['student', 'public']);
    }

    /**
     * Validate student ID for student users
     */
    private function validateStudentId($userId, $studentId)
    {
        $student = Student::where('student_id', $studentId)
                         ->where('user_id', $userId)
                         ->first();
        
        if (!$student) {
            throw ValidationException::withMessages([
                'student_id' => __('The provided student ID does not match.'),
            ]);
        }
    }
    
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration (both students and public users)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:11|unique:users,phone',
            'email' => 'nullable|email|max:255|unique:users,email',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
        ]);

        try {
            \DB::beginTransaction();

            // Determine user role - only allow public registration through this form
            $role = 'public';

            // Create user record
            $userData = [
                'name' => $request->name,
                'email' => $request->email ?? $request->phone . '@user.local',
                'phone' => $request->phone,
                'password' => Hash::make($request->phone),
                'role' => $role,
                'status' => 'active',
            ];

            // Add additional fields for public users
            if ($role === 'public') {
                $userData['date_of_birth'] = $request->date_of_birth;
                $userData['gender'] = $request->gender;
            }

            $user = User::create($userData);

            \DB::commit();

            // Log in the user
            Auth::login($user);

            return redirect()->route('home')
                ->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['registration_error' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}