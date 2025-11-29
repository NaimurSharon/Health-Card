<?php

namespace App\Http\Controllers;

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

        return redirect()->route('student.dashboard');
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