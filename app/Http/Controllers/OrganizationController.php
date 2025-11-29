<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Hospital;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    // Schools Section

    public function schoolsIndex(Request $request)
    {
        $query = School::with('users');
    
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
    
        $schools = $query->orderBy('name')->paginate(20);
        
        return view('backend.organizations.schools.index', compact('schools'));
    }

    public function schoolsCreate()
    {
        return view('backend.organizations.schools.form');
    }

    public function schoolsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:schools',
            'code' => 'required|string|max:50|unique:schools',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools',
            'principal_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        School::create($request->all());

        return redirect()->route('admin.organizations.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function schoolsShow(School $school)
    {
        $school->loadCount(['users']);
        return view('backend.organizations.schools.show', compact('school'));
    }

    public function schoolsEdit(School $school)
    {
        return view('backend.organizations.schools.form', compact('school'));
    }

    public function schoolsUpdate(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:schools,name,' . $school->id,
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'principal_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $school->update($request->all());

        return redirect()->route('admin.organizations.schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function schoolsDestroy(School $school)
    {
        if ($school->users()->count() > 0) {
            return redirect()->route('admin.organizations.schools.index')
                ->with('error', 'Cannot delete school that has users. Please transfer users first.');
        }

        $school->delete();

        return redirect()->route('admin.organizations.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    // Hospitals Section

    public function hospitalsIndex(Request $request)
    {
        $query = Hospital::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        $hospitals = $query->orderBy('name')->paginate(20);
        return view('backend.organizations.hospitals.index', compact('hospitals'));
    }

    public function hospitalsCreate()
    {
        return view('backend.organizations.hospitals.form');
    }

    public function hospitalsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,private,specialized,clinic',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:hospitals',
            'emergency_contact' => 'required|string|max:20',
            'website' => 'nullable|url',
            'services' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert services string to array if needed
        if ($request->has('services') && is_string($request->services)) {
            $validated['services'] = array_map('trim', explode(',', $request->services));
        }

        Hospital::create($validated);

        return redirect()->route('admin.organizations.hospitals.index')
            ->with('success', 'Hospital created successfully.');
    }

    public function hospitalsShow(Hospital $hospital)
    {
        $hospital->loadCount('doctors');
        return view('backend.organizations.hospitals.show', compact('hospital'));
    }

    public function hospitalsEdit(Hospital $hospital)
    {
        return view('backend.organizations.hospitals.form', compact('hospital'));
    }

    public function hospitalsUpdate(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,private,specialized,clinic',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:hospitals,email,' . $hospital->id,
            'emergency_contact' => 'required|string|max:20',
            'website' => 'nullable|url',
            'services' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert services string to array if needed
        if ($request->has('services') && is_string($request->services)) {
            $validated['services'] = array_map('trim', explode(',', $request->services));
        }

        $hospital->update($validated);

        return redirect()->route('admin.organizations.hospitals.index')
            ->with('success', 'Hospital updated successfully.');
    }

    public function hospitalsDestroy(Hospital $hospital)
    {
        if ($hospital->doctors()->count() > 0) {
            return redirect()->route('admin.organizations.hospitals.index')
                ->with('error', 'Cannot delete hospital that has doctors. Please transfer doctors first.');
        }

        $hospital->delete();

        return redirect()->route('admin.organizations.hospitals.index')
            ->with('success', 'Hospital deleted successfully.');
    }

    // Organization Dashboard
    public function index()
    {
        $schoolsCount = School::count();
        $hospitalsCount = Hospital::count();
        $activeSchools = School::where('status', 'active')->count();
        $activeHospitals = Hospital::where('status', 'active')->count();
        
        $recentSchools = School::latest()->take(5)->get();
        $recentHospitals = Hospital::latest()->take(5)->get();

        return view('backend.organizations.index', compact(
            'schoolsCount', 
            'hospitalsCount', 
            'activeSchools', 
            'activeHospitals',
            'recentSchools',
            'recentHospitals'
        ));
    }
}