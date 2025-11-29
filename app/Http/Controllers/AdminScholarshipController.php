<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipRegistration;
use App\Models\ScholarshipExam;
use App\Models\ExamAttempt;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminScholarshipController extends Controller
{
    // Show all scholarship registrations
    public function index(Request $request)
    {
        $query = ScholarshipRegistration::with(['student.user', 'exam', 'approver'])
            ->latest();

        // Filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('exam_id') && $request->exam_id !== 'all') {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('registration_number', 'like', "%{$search}%");
        }

        $registrations = $query->paginate(20);
        $exams = ScholarshipExam::where('status', 'upcoming')->get();
        
        $stats = [
            'total' => ScholarshipRegistration::count(),
            'pending' => ScholarshipRegistration::where('status', 'pending')->count(),
            'approved' => ScholarshipRegistration::where('status', 'approved')->count(),
            'rejected' => ScholarshipRegistration::where('status', 'rejected')->count(),
        ];

        return view('backend.scholarship.index', compact('registrations', 'exams', 'stats'));
    }

    // Show single registration details
    public function show(ScholarshipRegistration $registration)
    {
        $registration->load(['student.user', 'exam', 'approver']);
        
        // Get student's exam attempts for this exam
        $attempts = ExamAttempt::where('student_id', $registration->student_id)
            ->where('exam_id', $registration->exam_id)
            ->with('exam')
            ->get();

        return view('backend.scholarship.show', compact('registration', 'attempts'));
    }

    // Approve registration
    public function approve(Request $request, ScholarshipRegistration $registration)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $registration->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // TODO: Send notification to student about approval

        return redirect()->route('admin.scholarship.registrations')
            ->with('success', 'Registration approved successfully!');
    }

    // Reject registration
    public function reject(Request $request, ScholarshipRegistration $registration)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        $registration->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // TODO: Send notification to student about rejection

        return redirect()->route('admin.scholarship.registrations')
            ->with('success', 'Registration rejected successfully!');
    }

    // Set status to pending
    public function pending(ScholarshipRegistration $registration)
    {
        $registration->update([
            'status' => 'pending',
            'admin_notes' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()->route('admin.scholarship.registrations')
            ->with('success', 'Registration status set to pending!');
    }

    // Delete registration
    public function destroy(ScholarshipRegistration $registration)
    {
        $registration->delete();

        return redirect()->route('admin.scholarship.registrations')
            ->with('success', 'Registration deleted successfully!');
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'registrations' => 'required|array',
            'registrations.*' => 'exists:scholarship_registrations,id'
        ]);

        $count = 0;

        foreach ($request->registrations as $registrationId) {
            $registration = ScholarshipRegistration::find($registrationId);
            
            if ($request->action === 'approve') {
                $registration->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
                $count++;
            } elseif ($request->action === 'reject') {
                $registration->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
                $count++;
            } elseif ($request->action === 'delete') {
                $registration->delete();
                $count++;
            }
        }

        return redirect()->route('admin.scholarship.registrations')
            ->with('success', "{$count} registrations processed successfully!");
    }

    // Manage scholarship exams
    public function exams()
    {
        $exams = ScholarshipExam::withCount(['registrations', 'attempts'])
            ->latest()
            ->paginate(15);

        return view('backend.scholarship.exams.index', compact('exams'));
    }

    // Create exam form
    public function createExam()
    {
        return view('backend.scholarship.exams.create');
    }

    // Store new exam
    public function storeExam(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date|after:today',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|max:'.$request->total_marks,
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:4|max:4',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_answer' => 'required|integer|min:0|max:3',
            'questions.*.marks' => 'required|integer|min:1',
        ]);

        $validated['questions'] = json_encode($validated['questions']);
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'upcoming';

        ScholarshipExam::create($validated);

        return redirect()->route('admin.scholarship.exams')
            ->with('success', 'Scholarship exam created successfully!');
    }

    // Edit exam form
    public function editExam(ScholarshipExam $exam)
    {
        return view('backend.scholarship.exams.edit', compact('exam'));
    }

    // Update exam
    public function updateExam(Request $request, ScholarshipExam $exam)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|max:'.$request->total_marks,
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:4|max:4',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_answer' => 'required|integer|min:0|max:3',
            'questions.*.marks' => 'required|integer|min:1',
        ]);

        $validated['questions'] = json_encode($validated['questions']);

        $exam->update($validated);

        return redirect()->route('admin.scholarship.exams')
            ->with('success', 'Scholarship exam updated successfully!');
    }

    // Delete exam
    public function destroyExam(ScholarshipExam $exam)
    {
        // Check if there are any registrations or attempts
        if ($exam->registrations()->exists() || $exam->attempts()->exists()) {
            return redirect()->route('admin.scholarship.exams')
                ->with('error', 'Cannot delete exam that has registrations or attempts!');
        }

        $exam->delete();

        return redirect()->route('admin.scholarship.exams')
            ->with('success', 'Scholarship exam deleted successfully!');
    }

    // Reports and analytics
    public function reports()
    {
        $totalRegistrations = ScholarshipRegistration::count();
        $totalExams = ScholarshipExam::count();
        $totalAttempts = ExamAttempt::count();
        
        // Registration trends (last 30 days)
        $registrationTrends = ScholarshipRegistration::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution
        $statusDistribution = ScholarshipRegistration::select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Top performing students
        $topStudents = ExamAttempt::where('status', 'graded')
            ->with(['student.user', 'exam'])
            ->orderBy('score', 'desc')
            ->take(10)
            ->get();

        return view('backend.scholarship.reports.index', compact(
            'totalRegistrations',
            'totalExams',
            'totalAttempts',
            'registrationTrends',
            'statusDistribution',
            'topStudents'
        ));
    }

    // Export reports
    public function exportReports(Request $request)
    {
        // TODO: Implement CSV/Excel export
        return response()->json(['message' => 'Export feature coming soon']);
    }
}