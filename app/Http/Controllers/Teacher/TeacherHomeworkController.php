<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassDiary;
use App\Models\ClassSubject;
use Carbon\Carbon;

class TeacherHomeworkController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        try {
            $filterDate = $request->get('date', Carbon::today()->format('Y-m-d'));
            
            $homeworks = ClassDiary::where('teacher_id', $teacher->id)
                ->when($filterDate, function($query) use ($filterDate) {
                    return $query->where('entry_date', $filterDate);
                })
                ->with(['class', 'section', 'subject'])
                ->orderBy('entry_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $assignedClasses = ClassSubject::where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject'])
                ->get();

            return view('teacher.homework.index', compact(
                'teacher',
                'homeworks',
                'assignedClasses',
                'filterDate'
            ));

        } catch (\Exception $e) {
            return view('teacher.homework.index', [
                'teacher' => $teacher,
                'homeworks' => collect(),
                'assignedClasses' => collect(),
                'filterDate' => $request->get('date', Carbon::today()->format('Y-m-d')),
                'error' => 'Unable to load homework data.'
            ]);
        }
    }

    public function create()
    {
        $teacher = Auth::user();
        
        $assignedClasses = ClassSubject::where('teacher_id', $teacher->id)
            ->with(['class', 'section', 'subject'])
            ->get()
            ->groupBy('class_id');

        return view('teacher.homework.form', compact('teacher', 'assignedClasses'));
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
            'attachments.*' => 'file|max:10240',
        ]);

        try {
            // Verify the teacher is assigned to this class-subject combination
            $isAssigned = ClassSubject::where('teacher_id', $teacher->id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->exists();

            if (!$isAssigned) {
                return redirect()->back()
                    ->with('error', 'You are not assigned to teach this subject for the selected class and section.')
                    ->withInput();
            }

            $homework = ClassDiary::create([
                'teacher_id' => $teacher->id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'entry_date' => $request->entry_date,
                'homework_title' => $request->homework_title,
                'homework_description' => $request->homework_description,
                'due_date' => $request->due_date,
                'status' => 'active',
            ]);

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                $attachments = [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('homework-attachments', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                    ];
                }
                $homework->attachments = $attachments;
                $homework->save();
            }

            return redirect()->route('teacher.homework.index')
                ->with('success', 'Homework assigned successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to assign homework. Please try again.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $teacher = Auth::user();
        
        try {
            $homework = ClassDiary::where('id', $id)
                ->where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject'])
                ->firstOrFail();

            $assignedClasses = ClassSubject::where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject'])
                ->get()
                ->groupBy('class_id');

            return view('teacher.homework.form', compact('teacher', 'homework', 'assignedClasses'));

        } catch (\Exception $e) {
            return redirect()->route('teacher.homework.index')
                ->with('error', 'Homework not found or you do not have permission to edit it.');
        }
    }

    public function update(Request $request, $id)
    {
        $teacher = Auth::user();
        
        $request->validate([
            'homework_title' => 'required|string|max:255',
            'homework_description' => 'required|string',
            'due_date' => 'nullable|date|after_or_equal:entry_date',
            'status' => 'required|in:active,completed,cancelled',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);

        try {
            $homework = ClassDiary::where('id', $id)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            $homework->update([
                'homework_title' => $request->homework_title,
                'homework_description' => $request->homework_description,
                'due_date' => $request->due_date,
                'status' => $request->status,
            ]);

            // Handle new file uploads
            if ($request->hasFile('attachments')) {
                $currentAttachments = $homework->attachments ?? [];
                $newAttachments = [];
                
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('homework-attachments', 'public');
                    $newAttachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                    ];
                }
                
                $homework->attachments = array_merge($currentAttachments, $newAttachments);
                $homework->save();
            }

            return redirect()->route('teacher.homework.index')
                ->with('success', 'Homework updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update homework. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $teacher = Auth::user();
        
        try {
            $homework = ClassDiary::where('id', $id)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            $homework->delete();

            return redirect()->route('teacher.homework.index')
                ->with('success', 'Homework deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('teacher.homework.index')
                ->with('error', 'Failed to delete homework. Please try again.');
        }
    }

    public function getSections($classId)
    {
        $teacher = Auth::user();
        
        $sections = ClassSubject::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->with('section')
            ->get()
            ->pluck('section')
            ->unique();

        return response()->json($sections);
    }

    public function getSubjects($classId, $sectionId)
    {
        $teacher = Auth::user();
        
        $subjects = ClassSubject::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique();

        return response()->json($subjects);
    }
}