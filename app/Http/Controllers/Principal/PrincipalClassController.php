<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class PrincipalClassController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $classes = Classes::withCount(['students', 'sections'])
            ->where('school_id', $school->id)
            ->orderBy('numeric_value')
            ->paginate(15);
            
        return view('principal.classes.index', compact('classes'));
    }
    
    public function create()
    {
        $class = null;
        return view('principal.classes.form', compact('class'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'numeric_value' => 'required|integer',
            'shift' => 'required|in:morning,day',
            'capacity' => 'required|integer|min:1',
        ]);
        
        $school = auth()->user()->school;
        
        Classes::create([
            'name' => $request->name,
            'numeric_value' => $request->numeric_value,
            'shift' => $request->shift,
            'capacity' => $request->capacity,
            'school_id' => $school->id,
        ]);
        
        return redirect()->route('principal.classes.index')
            ->with('success', 'Class created successfully.');
    }
    
    public function edit($id)
    {
        $class = Classes::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        return view('principal.classes.form', compact('class'));
    }
    
    public function update(Request $request, $id)
    {
        $class = Classes::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        $request->validate([
            'name' => 'required|string|max:100',
            'numeric_value' => 'required|integer',
            'shift' => 'required|in:morning,day',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);
        
        $class->update($request->all());
        
        return redirect()->route('principal.classes.index')
            ->with('success', 'Class updated successfully.');
    }
    
    public function destroy($id)
    {
        $class = Classes::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        // Check if class has students
        if ($class->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete class with students. Please reassign students first.');
        }
        
        $class->delete();
        
        return redirect()->route('principal.classes.index')
            ->with('success', 'Class deleted successfully.');
    }
    
    public function sections($id)
    {
        $class = Classes::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        $sections = Section::withCount('students')
            ->where('class_id', $id)
            ->get();
            
        return view('principal.classes.sections', compact('class', 'sections'));
    }
}