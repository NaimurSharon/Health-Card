<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AnnualHealthRecord;
use App\Models\StudentHealthReport;
use App\Models\HealthReportCategory;
use App\Models\HealthReportField;
use App\Models\HealthCard;
use App\Models\User;
use App\Models\StudentHealthReportData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalHealthController extends Controller
{
    // ==================== HEALTH REPORTS ====================

    public function index(Request $request)
    {
        $school = auth()->user()->school;

        $query = StudentHealthReport::with(['student.user', 'student.class'])
            ->where('school_id', $school->id);

        // Filters
        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        if ($request->filled('from_date')) {
            $query->where('checkup_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('checkup_date', '<=', $request->to_date);
        }

        $healthReports = $query->latest()
            ->paginate(20);

        $classes = \App\Models\Classes::where('school_id', $school->id)->get();

        return view('principal.health-reports.index', compact('healthReports', 'classes'));
    }

    public function studentRecords(User $user)
    {
        $school = auth()->user()->school;

        // Verify student belongs to principal's school
        $student = Student::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        // Ensure health card exists
        $this->ensureHealthCardExists($student);

        // Get or create health report
        $healthReport = $this->ensureHealthReportExists($student);

        // Get all active categories with fields
        $categories = HealthReportCategory::with(['activeFields'])
            ->active()
            ->ordered()
            ->get();

        // Get annual health records for the student
        $annualRecords = AnnualHealthRecord::where('student_id', $student->id)
            ->orderBy('age', 'asc')
            ->get();


        return view('principal.health-reports.student', compact('user', 'student', 'healthReport', 'categories', 'annualRecords'));
    }

    public function storeOrUpdate(Request $request, User $user)
    {
        try {
            $school = auth()->user()->school;

            // Verify student belongs to principal's school
            $student = Student::where('user_id', $user->id)
                ->where('school_id', $school->id)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found or unauthorized access.'
                ], 404);
            }

            // Ensure health card exists
            $this->ensureHealthCardExists($student);

            // Get all active fields for validation
            $fields = HealthReportField::with('category')
                ->active()
                ->get();

            // Build validation rules
            $validationRules = [
                'checkup_date' => 'nullable|date',
                'checked_by' => 'nullable|string|max:255',
            ];

            // Add validation rules for each field
            foreach ($fields as $field) {
                $rule = $field->is_required ? 'required' : 'nullable';

                switch ($field->field_type) {
                    case 'number':
                        $rule .= '|numeric';
                        break;
                    case 'date':
                        $rule .= '|date';
                        break;
                    case 'select':
                        if ($field->options && count($field->options) > 0) {
                            $rule .= '|in:' . implode(',', $field->options);
                        }
                        break;
                    case 'checkbox':
                        $rule .= '|boolean';
                        break;
                    default:
                        $rule .= '|string';
                        break;
                }

                $validationRules[$field->field_name] = $rule;
            }

            // Validate the request
            $validatedData = $request->validate($validationRules);

            // Create or update health report
            $healthReport = StudentHealthReport::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'checkup_date' => $validatedData['checkup_date'] ?? now(),
                    'checked_by' => $validatedData['checked_by'] ?? auth()->user()->name,
                    'created_by' => auth()->id(),
                    'school_id' => $school->id,
                ]
            );

            // Save all field values
            foreach ($fields as $field) {
                $value = $request->get($field->field_name);

                // Handle different field types
                if ($field->field_type === 'checkbox') {
                    $value = $request->has($field->field_name) ? '1' : '0';
                }

                // For required fields, ensure we have a value
                if ($field->is_required && ($value === null || $value === '')) {
                    // Set default values for required fields based on type
                    switch ($field->field_type) {
                        case 'select':
                            $value = $field->options[0] ?? '';
                            break;
                        case 'number':
                            $value = 0;
                            break;
                        case 'checkbox':
                            $value = '0';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                }

                if ($value !== null && $value !== '') {
                    $healthReport->reportData()->updateOrCreate(
                        ['field_id' => $field->id],
                        ['field_value' => $value]
                    );
                } else {
                    // Remove if value is null/empty and field exists
                    $healthReport->reportData()->where('field_id', $field->id)->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Health report saved successfully.',
                'data' => $healthReport
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save health report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(StudentHealthReport $healthReport)
    {
        $school = auth()->user()->school;

        // Verify health report belongs to principal's school
        if ($healthReport->school_id !== $school->id) {
            abort(403, 'Unauthorized access.');
        }

        $student = $healthReport->student;
        $user = $student->user;

        // Get all active categories with fields
        $categories = HealthReportCategory::with(['activeFields'])
            ->active()
            ->ordered()
            ->get();

        return view('principal.health-reports.form', compact('healthReport', 'student', 'user', 'categories'));
    }

    public function update(Request $request, StudentHealthReport $healthReport)
    {
        try {
            $school = auth()->user()->school;

            // Verify health report belongs to principal's school
            if ($healthReport->school_id !== $school->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            // Get all active fields for validation
            $fields = HealthReportField::with('category')
                ->active()
                ->get();

            // Build validation rules
            $validationRules = [
                'checkup_date' => 'nullable|date',
                'checked_by' => 'nullable|string|max:255',
            ];

            // Add validation rules for each field
            foreach ($fields as $field) {
                $rule = $field->is_required ? 'required' : 'nullable';

                switch ($field->field_type) {
                    case 'number':
                        $rule .= '|numeric';
                        break;
                    case 'date':
                        $rule .= '|date';
                        break;
                    case 'select':
                        if ($field->options && count($field->options) > 0) {
                            $rule .= '|in:' . implode(',', $field->options);
                        }
                        break;
                    case 'checkbox':
                        $rule .= '|boolean';
                        break;
                    default:
                        $rule .= '|string';
                        break;
                }

                $validationRules[$field->field_name] = $rule;
            }

            // Validate the request
            $validatedData = $request->validate($validationRules);

            // Update health report
            $healthReport->update([
                'checkup_date' => $validatedData['checkup_date'] ?? $healthReport->checkup_date,
                'checked_by' => $validatedData['checked_by'] ?? $healthReport->checked_by,
            ]);

            // Save all field values
            foreach ($fields as $field) {
                $value = $request->get($field->field_name);

                // Handle different field types
                if ($field->field_type === 'checkbox') {
                    $value = $request->has($field->field_name) ? '1' : '0';
                }

                // For required fields, ensure we have a value
                if ($field->is_required && ($value === null || $value === '')) {
                    // Set default values for required fields based on type
                    switch ($field->field_type) {
                        case 'select':
                            $value = $field->options[0] ?? '';
                            break;
                        case 'number':
                            $value = 0;
                            break;
                        case 'checkbox':
                            $value = '0';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                }

                if ($value !== null && $value !== '') {
                    $healthReport->reportData()->updateOrCreate(
                        ['field_id' => $field->id],
                        ['field_value' => $value]
                    );
                } else {
                    // Remove if value is null/empty and field exists
                    $healthReport->reportData()->where('field_id', $field->id)->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Health report updated successfully.',
                'data' => $healthReport
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update health report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(StudentHealthReport $healthReport)
    {
        try {
            $school = auth()->user()->school;

            // Verify health report belongs to principal's school
            if ($healthReport->school_id !== $school->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            // Delete associated report data
            $healthReport->reportData()->delete();
            $healthReport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Health report deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete health report: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== ANNUAL HEALTH RECORDS ====================

    public function annualRecords(Request $request)
    {
        $school = auth()->user()->school;

        $query = AnnualHealthRecord::with(['student.user', 'student.class'])
            ->where('school_id', $school->id);

        // Filters
        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('age')) {
            $query->where('age', $request->age);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        $records = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $classes = \App\Models\Classes::where('school_id', $school->id)->get();

        return view('principal.health-reports.annual-records.index', compact('records', 'classes'));
    }

    public function studentAnnualRecords($studentId)
    {
        $school = auth()->user()->school;

        $student = Student::with(['user', 'class'])
            ->where('school_id', $school->id)
            ->findOrFail($studentId);

        $annualRecords = AnnualHealthRecord::where('student_id', $studentId)
            ->orderBy('age', 'asc')
            ->get();

        return view('principal.health-reports.annual-records.student', compact('student', 'annualRecords'));
    }

    public function createAnnualRecord()
    {
        $school = auth()->user()->school;
        $students = Student::with('user')
            ->where('school_id', $school->id)
            ->orderBy('class_id')
            ->orderBy('roll_number')
            ->get();

        return view('principal.health-reports.annual-records.form', compact('students'));
    }

    public function storeAnnualRecord(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'age' => 'required|integer|min:3|max:18',
            'weight' => 'required|numeric|min:10|max:100',
            'height' => 'required|numeric|min:50|max:200',
            'head_circumference' => 'nullable|numeric|min:30|max:60',
            'development_notes' => 'nullable|string',
            'difficulties' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'general_health' => 'required|string',
            'vaccination_status' => 'required|string',
            'nutrition_notes' => 'nullable|string',
        ]);

        $school = auth()->user()->school;

        // Verify student belongs to principal's school
        $student = Student::where('id', $request->student_id)
            ->where('school_id', $school->id)
            ->first();

        if (!$student) {
            return redirect()->back()
                ->with('error', 'Student not found or unauthorized access.')
                ->withInput();
        }

        // Check if record exists for this age
        $existingRecord = AnnualHealthRecord::where('student_id', $request->student_id)
            ->where('age', $request->age)
            ->first();

        if ($existingRecord) {
            return redirect()->back()
                ->with('error', 'A health record already exists for this student at age ' . $request->age)
                ->withInput();
        }

        AnnualHealthRecord::create([
            'student_id' => $request->student_id,
            'school_id' => $school->id,
            'age' => $request->age,
            'weight' => $request->weight,
            'height' => $request->height,
            'head_circumference' => $request->head_circumference,
            'development_notes' => $request->development_notes,
            'difficulties' => $request->difficulties,
            'special_instructions' => $request->special_instructions,
            'general_health' => $request->general_health,
            'vaccination_status' => $request->vaccination_status,
            'nutrition_notes' => $request->nutrition_notes,
            'recorded_by' => auth()->id(),
        ]);

        return redirect()->route('principal.health.annual-records.index')
            ->with('success', 'Health record created successfully.');
    }

    public function editAnnualRecord(AnnualHealthRecord $annualHealthRecord)
    {
        $school = auth()->user()->school;

        // Verify record belongs to principal's school
        if ($annualHealthRecord->school_id !== $school->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('principal.health-reports.annual-records.form', compact('annualHealthRecord'));
    }

    public function updateAnnualRecord(Request $request, AnnualHealthRecord $annualHealthRecord)
    {
        $school = auth()->user()->school;

        // Verify record belongs to principal's school
        if ($annualHealthRecord->school_id !== $school->id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'age' => 'required|integer|min:3|max:18',
            'weight' => 'required|numeric|min:10|max:100',
            'height' => 'required|numeric|min:50|max:200',
            'head_circumference' => 'nullable|numeric|min:30|max:60',
            'development_notes' => 'nullable|string',
            'difficulties' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'general_health' => 'required|string',
            'vaccination_status' => 'required|string',
            'nutrition_notes' => 'nullable|string',
        ]);

        $annualHealthRecord->update($request->all());

        return redirect()->route('principal.health.annual-records.index')
            ->with('success', 'Health record updated successfully.');
    }

    public function destroyAnnualRecord(AnnualHealthRecord $annualHealthRecord)
    {
        $school = auth()->user()->school;

        // Verify record belongs to principal's school
        if ($annualHealthRecord->school_id !== $school->id) {
            abort(403, 'Unauthorized access.');
        }

        $annualHealthRecord->delete();

        return redirect()->route('principal.health.annual-records.index')
            ->with('success', 'Health record deleted successfully.');
    }

    // ==================== HELPER METHODS ====================

    private function ensureHealthCardExists(Student $student)
    {
        $healthCard = HealthCard::where('user_id', $student->user_id)->first();

        if (!$healthCard) {
            // Generate card number
            $cardNumber = 'HC-' . strtoupper(substr(md5(uniqid()), 0, 8)) . '-' . date('Y');

            // Create health card
            $healthCard = HealthCard::create([
                'user_id' => $student->user_id,
                'card_number' => $cardNumber,
                'issue_date' => now(),
                'expiry_date' => now()->addYear(),
                'status' => 'active',
                'medical_summary' => 'Auto-generated health card',
                'emergency_instructions' => 'Contact parent/guardian',
            ]);
        }

        return $healthCard;
    }

    private function ensureHealthReportExists(Student $student)
    {
        $healthReport = StudentHealthReport::where('student_id', $student->id)->first();

        if (!$healthReport) {
            // Create a basic health report with default values
            $healthReport = StudentHealthReport::create([
                'student_id' => $student->id,
                'checkup_date' => now(),
                'checked_by' => auth()->user()->name,
                'created_by' => auth()->id(),
                'school_id' => $student->school_id,
            ]);

            // Set default values for required fields
            $requiredFields = HealthReportField::active()->required()->get();
            foreach ($requiredFields as $field) {
                $defaultValue = $this->getDefaultValueForField($field, $student);
                $healthReport->reportData()->create([
                    'field_id' => $field->id,
                    'field_value' => $defaultValue
                ]);
            }
        }

        return $healthReport;
    }

    private function getDefaultValueForField(HealthReportField $field, Student $student)
    {
        switch ($field->field_name) {
            case 'age_group':
                return $this->calculateAgeGroup($student);
            case 'weight_kg':
            case 'height_cm':
                return '0';
            case 'checkup_date':
                return now()->format('Y-m-d');
            case 'checked_by':
                return auth()->user()->name;
            default:
                if ($field->field_type === 'select' && $field->options) {
                    return $field->options[0] ?? '';
                }
                return '';
        }
    }

    private function calculateAgeGroup(Student $student)
    {
        if (!$student->user->date_of_birth) {
            return '6-12'; // Default age group
        }

        $age = $student->user->date_of_birth->age;

        if ($age <= 1)
            return '0-1';
        if ($age <= 5)
            return '2-5';
        if ($age <= 12)
            return '6-12';
        return '13-18';
    }
}