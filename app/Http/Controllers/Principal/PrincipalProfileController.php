<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PrincipalProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('principal.profile.index', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only(['name', 'email', 'phone', 'date_of_birth', 'gender', 'address']);
        
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($user->profile_image) {
                \Storage::delete('public/' . $user->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $data['profile_image'] = $path;
        }
        
        $user->update($data);
        
        return redirect()->route('principal.profile.index')
            ->with('success', 'Profile updated successfully.');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.');
        }
        
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        
        return redirect()->route('principal.profile.index')
            ->with('success', 'Password updated successfully.');
    }
    
    public function editSchool()
    {
        $school = auth()->user()->school;
        return view('principal.profile.edit-school', compact('school'));
    }
    
    public function updateSchool(Request $request)
    {
        $school = auth()->user()->school;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'type' => 'required|in:government,private,madrasa,international',
            'established_year' => 'required|digits:4',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'motto' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'academic_system' => 'required|in:national,cambridge,ib,other',
            'medium' => 'required|in:bangla,english,both',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only([
            'name', 'code', 'type', 'established_year', 'address', 
            'city', 'district', 'phone', 'email', 'website', 
            'motto', 'vision', 'mission', 'academic_system', 'medium'
        ]);
        
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($school->logo) {
                \Storage::delete('public/' . $school->logo);
            }
            
            $path = $request->file('logo')->store('school-logos', 'public');
            $data['logo'] = $path;
        }
        
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($school->cover_image) {
                \Storage::delete('public/' . $school->cover_image);
            }
            
            $path = $request->file('cover_image')->store('school-covers', 'public');
            $data['cover_image'] = $path;
        }
        
        $school->update($data);
        
        return redirect()->route('principal.school.edit')
            ->with('success', 'School information updated successfully.');
    }
}