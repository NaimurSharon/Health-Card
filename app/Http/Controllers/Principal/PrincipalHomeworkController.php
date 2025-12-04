<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\ClassDiary;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrincipalHomeworkController extends Controller
{
    public function index(Request $request)
    {
        $school = auth()->user()->school;

        $query = ClassDiary::with(['class', 'section', 'subject', 'teacher'])
            ->where('school_id', $school->id)
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('search')) {
            $query->where('homework_title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $homeworks = $query->paginate(10)->withQueryString();

        // Get all classes for the dropdown
        $classes = \App\Models\Classes::where('school_id', $school->id)->orderBy('name')->get();

        return view('principal.homework.index', compact('homeworks', 'classes'));
    }



    public function create()
    {
        $school = auth()->user()->school;
        $classes = Classes::where('school_id', $school->id)->get();
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->get();

        return view('principal.homework.form', compact('classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'entry_date' => 'required|date',
            'homework_title' => 'required|string|max:255',
            'homework_description' => 'required|string',
            'due_date' => 'nullable|date|after:entry_date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $school = auth()->user()->school;

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('homework-attachments', 'public');
                $attachments[] = $path;
            }
        }

        ClassDiary::create([
            'teacher_id' => $request->teacher_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'entry_date' => $request->entry_date,
            'homework_title' => $request->homework_title,
            'homework_description' => $request->homework_description,
            'due_date' => $request->due_date,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null,
            'status' => 'active',
            'school_id' => $school->id,
        ]);

        return redirect()->route('principal.homework.index')
            ->with('success', 'Homework created successfully.');
    }

    public function edit($id)
    {
        $school = auth()->user()->school;
        $homework = ClassDiary::where('school_id', $school->id)->findOrFail($id);

        $classes = Classes::where('school_id', $school->id)->get();
        $sections = Section::where('class_id', $homework->class_id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->get();

        return view('principal.homework.form', compact('homework', 'classes', 'sections', 'subjects', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $school = auth()->user()->school;
        $homework = ClassDiary::where('school_id', $school->id)->findOrFail($id);

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'entry_date' => 'required|date',
            'homework_title' => 'required|string|max:255',
            'homework_description' => 'required|string',
            'due_date' => 'nullable|date|after:entry_date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        // Handle file uploads
        $attachments = json_decode($homework->attachments, true) ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('homework-attachments', 'public');
                $attachments[] = $path;
            }
        }

        $homework->update([
            'teacher_id' => $request->teacher_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'entry_date' => $request->entry_date,
            'homework_title' => $request->homework_title,
            'homework_description' => $request->homework_description,
            'due_date' => $request->due_date,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null,
            'status' => $request->status,
        ]);

        return redirect()->route('principal.homework.index')
            ->with('success', 'Homework updated successfully.');
    }

    public function destroy($id)
    {
        $school = auth()->user()->school;
        $homework = ClassDiary::where('school_id', $school->id)->findOrFail($id);

        // Delete attached files
        if ($homework->attachments) {
            $attachments = json_decode($homework->attachments, true);
            foreach ($attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $homework->delete();

        return redirect()->route('principal.homework.index')
            ->with('success', 'Homework deleted successfully.');
    }

    public function getSections($classId)
    {
        $sections = Section::where('class_id', $classId)->get();
        return response()->json($sections);
    }

    public function getSubjects($classId, $sectionId)
    {
        $subjects = \App\Models\ClassSubject::with('subject')
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->get()
            ->pluck('subject');

        return response()->json($subjects);
    }

    public function removeAttachment($id, $attachmentIndex)
    {
        $school = auth()->user()->school;
        $homework = ClassDiary::where('school_id', $school->id)->findOrFail($id);

        $attachments = json_decode($homework->attachments, true) ?? [];

        if (isset($attachments[$attachmentIndex])) {
            // Delete file from storage
            Storage::disk('public')->delete($attachments[$attachmentIndex]);

            // Remove from array
            unset($attachments[$attachmentIndex]);
            $attachments = array_values($attachments); // Reindex array

            $homework->update([
                'attachments' => !empty($attachments) ? json_encode($attachments) : null,
            ]);
        }

        return response()->json(['success' => true]);
    }
}