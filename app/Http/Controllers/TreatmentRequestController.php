<?php

namespace App\Http\Controllers;

use App\Models\TreatmentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class TreatmentRequestController extends Controller
{
    public function index(Request $request)
    {   
        $query = TreatmentRequest::with(['student.user', 'doctor']);

        // Get the filter values from request
        $status = $request->input('status', '');
        $priority = $request->input('priority', '');
        $studentId = $request->input('student_id', '');

        if ($status) {
            $query->where('status', $status);
        }
        
        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $treatmentRequests = $query->orderBy('created_at', 'desc')->paginate(20);
        $students = Student::with('user')->get();

        // Pass all filter variables to the view
        return view('backend.treatment-requests.index', compact(
            'treatmentRequests', 
            'students',
            'status',
            'priority',
            'studentId'
        ));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $doctors = User::where('role', 'medical_staff')->where('status', 'active')->get();
        
        return view('backend.treatment-requests.form', compact('students', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'doctor_id' => 'required|exists:users,id',
            'symptoms' => 'required|string',
            'priority' => 'required|in:low,medium,high,emergency',
            'requested_date' => 'required|date',
        ]);

        TreatmentRequest::create([
            'student_id' => $request->student_id,
            'doctor_id' => $request->doctor_id,
            'symptoms' => $request->symptoms,
            'priority' => $request->priority,
            'requested_date' => $request->requested_date,
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        return redirect()->route('admin.treatment-requests.index')
            ->with('success', 'Treatment request created successfully.');
    }

    public function show(TreatmentRequest $treatmentRequest)
    {
        $treatmentRequest->load(['student.user', 'doctor', 'requestedBy']);
        return view('backend.treatment-requests.show', compact('treatmentRequest'));
    }

    public function edit(TreatmentRequest $treatmentRequest)
    {
        $students = Student::with('user')->get();
        $doctors = User::where('role', 'doctor')->where('status', 'active')->get();
        
        return view('backend.treatment-requests.form', compact('treatmentRequest', 'students', 'doctors'));
    }

    public function update(Request $request, TreatmentRequest $treatmentRequest)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'assigned_doctor' => 'required|exists:users,id',
            'symptoms' => 'required|string',
            'priority' => 'required|in:low,medium,high,emergency',
            'requested_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected,completed',
            'doctor_notes' => 'nullable|string',
        ]);

        $treatmentRequest->update($request->all());

        return redirect()->route('admin.treatment-requests.index')
            ->with('success', 'Treatment request updated successfully.');
    }
    
        public function doctorIndex(Request $request)
    {
        $doctorId = auth()->id();
        $status = $request->get('status');

        $treatmentRequests = TreatmentRequest::with(['student.user', 'requestedBy'])
            ->forDoctor($doctorId);

        if ($status) {
            $treatmentRequests->where('status', $status);
        }

        $treatmentRequests = $treatmentRequests->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $students = Student::with('user')->get();

        return view('doctor.treatment-requests.index', compact('treatmentRequests', 'status','students'));
    }
    
    public function doctorView(TreatmentRequest $treatmentRequest)
    {
        if ($treatmentRequest->assigned_doctor !== auth()->id()) {
            abort(403);
        }

        $treatmentRequest->load(['student.user', 'requestedBy', 'doctor']);
        return view('doctor.treatment-requests.show', compact('treatmentRequest'));
    }

    public function doctorUpdate(Request $request, TreatmentRequest $treatmentRequest)
    {
        if ($treatmentRequest->assigned_doctor !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'notes' => 'nullable|string'
        ]);

        $treatmentRequest->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Treatment request updated successfully.');
    }

    public function destroy(TreatmentRequest $treatmentRequest)
    {
        $treatmentRequest->delete();

        return redirect()->route('admin.treatment-requests.index')
            ->with('success', 'Treatment request deleted successfully.');
    }

}