<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;

class PrincipalSectionController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $sections = Section::with(['class', 'teacher', 'students'])
            ->where('school_id', $school->id)
            ->orderBy('class_id')
            ->orderBy('name')
            ->paginate(15);
            
        return view('principal.sections.index', compact('sections'));
    }
    
    public function create()
    {
        $school = auth()->user()->school;
        $classes = Classes::where('school_id', $school->id)->get();
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->get();
            
        return view('principal.sections.form', compact('classes', 'teachers'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:100',
            'room_number' => 'nullable|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1',
        ]);
        
        $school = auth()->user()->school;
        
        Section::create([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'room_number' => $request->room_number,
            'teacher_id' => $request->teacher_id,
            'capacity' => $request->capacity,
            'school_id' => $school->id,
        ]);
        
        return redirect()->route('principal.sections.index')
            ->with('success', 'Section created successfully.');
    }
    
    public function edit($id)
    {
        $school = auth()->user()->school;
        $section = Section::where('school_id', $school->id)->findOrFail($id);
        $classes = Classes::where('school_id', $school->id)->get();
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->get();
            
        return view('principal.sections.form', compact('section', 'classes', 'teachers'));
    }
    
    public function update(Request $request, $id)
    {
        $section = Section::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:100',
            'room_number' => 'nullable|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);
        
        $section->update($request->all());
        
        return redirect()->route('principal.sections.index')
            ->with('success', 'Section updated successfully.');
    }
    
    public function destroy($id)
    {
        $section = Section::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        // Check if section has students
        if ($section->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete section with students. Please reassign students first.');
        }
        
        $section->delete();
        
        return redirect()->route('principal.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}