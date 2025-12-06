<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Section;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PrincipalTeacherController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $teachers = Teacher::with(['user', 'classTeacherOf', 'sections.class'])
            ->where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('principal.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $teacher = null;
        return view('principal.teachers.form', compact('teacher'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'qualifications' => 'required|string',
            'specialization' => 'required|string',
            'teacher_id' => 'nullable|string|max:50|unique:teachers,teacher_id',
            'designation' => 'required|in:headmaster,assistant_headmaster,senior_teacher,assistant_teacher,guest_teacher',
            'department' => 'nullable|string|max:255',
            'nid_number' => 'nullable|string|max:50',
            'birth_certificate' => 'nullable|string|max:50',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:20',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'disabilities' => 'nullable|string',
        ]);

        $school = auth()->user()->school;

        DB::beginTransaction();

        try {
            // Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make('teacher@123'), // Default password
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'qualifications' => $request->qualifications,
                'specialization' => $request->specialization,
                'role' => 'teacher',
                'school_id' => $school->id,
                'status' => 'active',
            ]);

            // Generate teacher ID if not provided
            $teacherId = $request->teacher_id ?? $this->generateTeacherId($school);

            // Create Teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'school_id' => $school->id,
                'teacher_id' => $teacherId,
                'designation' => $request->designation,
                'department' => $request->department,
                'nid_number' => $request->nid_number,
                'birth_certificate' => $request->birth_certificate,
                'marital_status' => $request->marital_status,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'emergency_contact' => $request->emergency_contact,
                'blood_group' => $request->blood_group,
                'medical_conditions' => $request->medical_conditions ? explode(',', $request->medical_conditions) : null,
                'allergies' => $request->allergies ? explode(',', $request->allergies) : null,
                'disabilities' => $request->disabilities ? explode(',', $request->disabilities) : null,
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('principal.teachers.index')
                ->with('success', 'Teacher created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to create teacher: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $teacher = Teacher::with([
            'user',
            'classTeacherOf',
            'sections.class',
            'subjects.subject',
            'subjects.class',
            'subjects.section'
        ])
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        return view('principal.teachers.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        return view('principal.teachers.form', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:100',
            'qualifications' => 'required|string',
            'specialization' => 'required|string',
            'teacher_id' => 'nullable|string|max:50|unique:teachers,teacher_id,' . $teacher->id,
            'designation' => 'required|in:headmaster,assistant_headmaster,senior_teacher,assistant_teacher,guest_teacher',
            'department' => 'nullable|string|max:255',
            'nid_number' => 'nullable|string|max:50',
            'birth_certificate' => 'nullable|string|max:50',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:20',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'disabilities' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update User
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'qualifications' => $request->qualifications,
                'specialization' => $request->specialization,
            ]);

            // Update Teacher
            $teacher->update([
                'teacher_id' => $request->teacher_id ?? $teacher->teacher_id,
                'designation' => $request->designation,
                'department' => $request->department,
                'nid_number' => $request->nid_number,
                'birth_certificate' => $request->birth_certificate,
                'marital_status' => $request->marital_status,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'emergency_contact' => $request->emergency_contact,
                'blood_group' => $request->blood_group,
                'medical_conditions' => $request->medical_conditions ? explode(',', $request->medical_conditions) : null,
                'allergies' => $request->allergies ? explode(',', $request->allergies) : null,
                'disabilities' => $request->disabilities ? explode(',', $request->disabilities) : null,
            ]);

            DB::commit();

            return redirect()->route('principal.teachers.index')
                ->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update teacher: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        // Check if teacher has any assignments
        if ($teacher->sections()->exists() || $teacher->subjects()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete teacher who has assigned classes or subjects. Remove assignments first.');
        }

        DB::beginTransaction();

        try {
            // Delete user
            $teacher->user->delete();
            // Teacher record will be deleted automatically due to cascade
            DB::commit();

            return redirect()->route('principal.teachers.index')
                ->with('success', 'Teacher deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    public function assignClasses($id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        $classes = Classes::with('sections')->where('school_id', auth()->user()->school->id)->get();
        $assignedClasses = ClassSubject::where('teacher_id', $teacher->user_id)->get();

        return view('principal.teachers.assign-classes', compact('teacher', 'classes', 'assignedClasses'));
    }

    public function storeAssignClasses(Request $request, $id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        $request->validate([
            'class_subjects' => 'required|array',
            'class_subjects.*.class_id' => 'required|exists:classes,id',
            'class_subjects.*.section_id' => 'required|exists:sections,id',
            'class_subjects.*.subject_id' => 'required|exists:subjects,id',
        ]);

        // Remove existing assignments
        ClassSubject::where('teacher_id', $teacher->user_id)->delete();

        // Add new assignments
        foreach ($request->class_subjects as $assignment) {
            ClassSubject::create([
                'class_id' => $assignment['class_id'],
                'section_id' => $assignment['section_id'],
                'subject_id' => $assignment['subject_id'],
                'teacher_id' => $teacher->user_id,
            ]);
        }

        return redirect()->route('principal.teachers.show', $id)
            ->with('success', 'Classes assigned successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended,on_leave',
        ]);

        $teacher = Teacher::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        $teacher->update([
            'status' => $request->status,
        ]);

        // Also update user status
        $teacher->user->update([
            'status' => $request->status,
        ]);

        return redirect()->back()
            ->with('success', 'Teacher status updated successfully.');
    }

    private function generateTeacherId($school)
    {
        // Generate unique teacher ID: SCH-SCHOOLID-YEAR-0001
        $schoolCode = strtoupper(substr($school->name, 0, 3));
        $year = date('Y');

        // Get last teacher ID for this school
        $lastTeacher = Teacher::where('school_id', $school->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTeacher && preg_match('/\d+$/', $lastTeacher->teacher_id, $matches)) {
            $nextNumber = intval($matches[0]) + 1;
        } else {
            $nextNumber = 1;
        }

        return "TCH-{$schoolCode}-{$year}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}