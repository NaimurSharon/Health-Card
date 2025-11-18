<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
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
        return view('backend.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('backend.hospitals.form');
    }

    public function store(Request $request)
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

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital created successfully.');
    }

    public function show(Hospital $hospital)
    {
        $hospital->loadCount('doctors');
        return view('backend.hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        return view('backend.hospitals.form', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
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

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital updated successfully.');
    }

    public function destroy(Hospital $hospital)
    {
        if ($hospital->doctors()->count() > 0) {
            return redirect()->route('admin.hospitals.index')
                ->with('error', 'Cannot delete hospital that has doctors. Please transfer doctors first.');
        }

        $hospital->delete();

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital deleted successfully.');
    }
}