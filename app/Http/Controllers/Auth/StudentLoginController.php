<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StudentLoginController extends Controller
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
        $request->validate([
            'phone' => 'required|string|max:11',
            'student_id' => 'nullable|string',
        ]);

        // Clean the phone number (remove any formatting)
        $phone = preg_replace('/\D/', '', $request->phone);
        
        // Find student by emergency_contact (phone) and active status
        $student = Student::where('emergency_contact', $phone)
                         ->where('status', 'active')
                         ->first();

        // If student_id is provided, use it for additional verification
        if ($request->filled('student_id')) {
            $student = Student::where('student_id', $request->student_id)
                             ->where('emergency_contact', $phone)
                             ->where('status', 'active')
                             ->first();
        }

        if (!$student) {
            throw ValidationException::withMessages([
                'phone' => __('The provided credentials do not match our records.'),
            ]);
        }

        // Check if user record exists and is active
        $user = $student->user;
        if (!$user || $user->status !== 'active') {
            throw ValidationException::withMessages([
                'phone' => __('Your student account is not active. Please contact administration.'),
            ]);
        }

        // Log in the student
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->route('home');
    }
    
    /**
 * Handle student registration
 */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:11|unique:students,emergency_contact',
            'email' => 'nullable|email|max:255',
            'student_id' => 'nullable|string|max:50',
        ]);
    
        try {
            // Create student record
            $student = Student::create([
                'name' => $request->name,
                'emergency_contact' => $request->phone,
                'student_id' => $request->student_id,
                'status' => 'active',
            ]);
    
            // Create user record
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email ?? $request->phone . '@student.local',
                'phone' => $request->phone,
                'password' => Hash::make($request->phone), // Use phone as default password
                'role' => 'student',
                'school_id' => null, // You might want to set this based on your logic
                'status' => 'active',
            ]);
    
            // Link student to user
            $student->update(['user_id' => $user->id]);
    
            // Log in the student
            Auth::login($user);
    
            return response()->json([
                'success' => true,
                'message' => 'Registration successful!'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
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