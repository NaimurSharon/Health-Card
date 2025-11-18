<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
    
        $user = Auth::user(); // Get the logged-in user
    
        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
    
            case 'doctor':
                return redirect()->route('doctor.dashboard');
    
            case 'student':
                return redirect()->route('student.dashboard');
                
            case 'teacher':
                return redirect()->route('teacher.dashboard');
    
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'role' => 'Your account does not have a valid role assigned.',
                ]);
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
