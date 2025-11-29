<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassDiaryController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        $homeworks = \App\Models\ClassDiary::where('teacher_id', $teacher->id)
            ->with(['class', 'section', 'subject'])
            ->orderBy('entry_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $classes = \App\Models\Classes::where('status', 'active')->get();
        $subjects = \App\Models\Subject::where('status', 'active')->get();

        return view('teacher.class-diary.index', compact('homeworks', 'classes', 'subjects'));
    }

    public function create()
    {
        $teacher = Auth::user();
        
        $classes = \App\Models\Classes::where('status', 'active')->get();
        $subjects = \App\Models\Subject::where('status', 'active')->get();

        return view('teacher.class-diary.create', compact('classes', 'subjects'));
    }

    public function getSections($classId)
    {
        $sections = \App\Models\Section::where('class_id', $classId)
            ->where('status', 'active')
            ->get();

        return response()->json($sections);
    }

    public function store(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'entry_date' => 'required|date',
            'homework_title' => 'required|string|max:255',
            'homework_description' => 'required|string',
            'due_date' => 'nullable|date|after_or_equal:entry_date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('homework-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize()
                ];
            }
        }

        \App\Models\ClassDiary::create([
            'teacher_id' => $teacher->id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'entry_date' => $request->entry_date,
            'homework_title' => $request->homework_title,
            'homework_description' => $request->homework_description,
            'due_date' => $request->due_date,
            'attachments' => $attachments,
            'status' => 'active'
        ]);

        return redirect()->route('teacher.class-diary.index')
            ->with('success', 'Homework assigned successfully!');
    }

    public function edit($id)
    {
        $teacher = Auth::user();
        
        $homework = \App\Models\ClassDiary::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $classes = \App\Models\Classes::where('status', 'active')->get();
        $sections = \App\Models\Section::where('class_id', $homework->class_id)
            ->where('status', 'active')
            ->get();
        $subjects = \App\Models\Subject::where('status', 'active')->get();

        return view('teacher.class-diary.edit', compact('homework', 'classes', 'sections', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Auth::user();

        $homework = \App\Models\ClassDiary::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'entry_date' => 'required|date',
            'homework_title' => 'required|string|max:255',
            'homework_description' => 'required|string',
            'due_date' => 'nullable|date|after_or_equal:entry_date',
            'status' => 'required|in:active,completed,cancelled'
        ]);

        $homework->update([
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'entry_date' => $request->entry_date,
            'homework_title' => $request->homework_title,
            'homework_description' => $request->homework_description,
            'due_date' => $request->due_date,
            'status' => $request->status
        ]);

        return redirect()->route('teacher.class-diary.index')
            ->with('success', 'Homework updated successfully!');
    }

    public function destroy($id)
    {
        $teacher = Auth::user();

        $homework = \App\Models\ClassDiary::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $homework->delete();

        return redirect()->route('teacher.class-diary.index')
            ->with('success', 'Homework deleted successfully!');
    }
}