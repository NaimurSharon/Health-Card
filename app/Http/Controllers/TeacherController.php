<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher')
            ->withCount(['classSubjects as classes_count' => function($query) {
                $query->select(\DB::raw('count(distinct class_id)'));
            }]);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $teachers = $query->orderBy('name')->paginate(10);

        return view('backend.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::active()->get();
        $classes = Classes::active()->get();
        return view('backend.teachers.form', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
            'status' => 'active',
        ]);

        // Attach subjects (classes will be handled through class_subjects table)
        if ($request->has('subjects')) {
            $teacher->subjects()->attach($request->subjects);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(User $teacher)
    {
        $teacher->load(['subjects', 'classSubjects.class', 'classSubjects.section']);
        
        // Get unique classes taught by this teacher
        $classes = $teacher->classSubjects->groupBy('class_id')->map(function($items) {
            $class = $items->first()->class;
            $sections = $items->pluck('section.name')->filter()->unique()->implode(', ');
            return [
                'class' => $class,
                'sections' => $sections
            ];
        });

        return view('backend.teachers.show', compact('teacher', 'classes'));
    }

    public function edit(User $teacher)
    {
        $subjects = Subject::active()->get();
        $teacher->load(['subjects', 'classSubjects']);
        
        return view('backend.teachers.form', compact('teacher', 'subjects'));
    }

    public function update(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $teacher->update($updateData);

        // Sync subjects
        $teacher->subjects()->sync($request->subjects ?? []);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        // Check if teacher has any class subjects assigned
        if ($teacher->classSubjects()->count() > 0) {
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Cannot delete teacher. Teacher is assigned to classes.');
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}