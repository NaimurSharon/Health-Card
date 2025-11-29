<?php

namespace App\Http\Controllers;

use App\Models\StudentHealthReport;
use App\Models\HealthReportCategory;
use App\Models\HealthReportField;
use App\Models\HealthCard;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentHealthReportData;
use Illuminate\Http\Request;

class HealthReportController extends Controller
{
    public function index()
    {
        $healthReports = StudentHealthReport::with(['student.user', 'student.class'])
            ->latest()
            ->paginate(20);

        $classes = \App\Models\Classes::all();

        return view('backend.health-reports.index', compact('healthReports', 'classes'));
    }

    public function showByStudent(User $user)
    {
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Student record not found for this user.');
        }

        // Ensure health card exists
        $this->ensureHealthCardExists($student);

        // Get or create health report
        $healthReport = $this->ensureHealthReportExists($student);
        
        // Get all active categories with fields
        $categories = HealthReportCategory::with(['activeFields'])
            ->active()
            ->ordered()
            ->get();

        return view('backend.health-reports.show', compact('user', 'student', 'healthReport', 'categories'));
    }

    public function storeOrUpdate(Request $request, User $user)
    {
        try {
            $student = Student::where('user_id', $user->id)->first();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found for this user.'
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
                    'school_id' => $user->school_id,
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

    // Field Management Methods
    public function manageFields()
    {
        $categories = HealthReportCategory::with(['fields' => function($query) {
            $query->ordered();
        }])->ordered()->get();

        return view('backend.health-reports.manage-fields', compact('categories'));
    }

    /**
     * Show the form for editing the specified field.
     */
    public function editField(HealthReportField $field)
    {
        try {
            return response()->json([
                'success' => true,
                'field' => [
                    'id' => $field->id,
                    'category_id' => $field->category_id,
                    'label' => $field->label,
                    'field_type' => $field->field_type,
                    'field_name' => $field->field_name,
                    'placeholder' => $field->placeholder,
                    'options' => $field->options,
                    'sort_order' => $field->sort_order,
                    'is_required' => (bool)$field->is_required,
                    'is_active' => (bool)$field->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load field data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createField(Request $request)
    {
        try {
            
            $validator = \Validator::make($request->all(), [
                'category_id' => 'required|exists:health_report_categories,id',
                'label' => 'required|string|max:255',
                'field_type' => 'required|in:text,number,date,select,checkbox,textarea',
                'field_name' => 'required|string|max:255|unique:health_report_fields,field_name',
                'placeholder' => 'nullable|string|max:255',
                'options' => 'nullable|string',
                'is_required' => 'boolean',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            // Parse options if provided
            $options = null;
            if ($request->filled('options') && $request->field_type === 'select') {
                $optionsArray = array_filter(
                    array_map('trim', explode("\n", $request->options)),
                    function($option) {
                        return !empty($option);
                    }
                );
                $options = $optionsArray;
            }
    
    
            $field = HealthReportField::create([
                'category_id' => $request->category_id,
                'label' => $request->label,
                'field_type' => $request->field_type,
                'field_name' => $request->field_name,
                'placeholder' => $request->placeholder,
                'options' => $options,
                'is_required' => $request->boolean('is_required'),
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->sort_order ?? 0,
            ]);
    
    
            return response()->json([
                'success' => true,
                'message' => 'Field created successfully.',
                'field' => $field
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create field: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateField(Request $request, HealthReportField $field)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:health_report_categories,id',
                'label' => 'required|string|max:255',
                'field_type' => 'required|in:text,number,date,select,checkbox,textarea',
                'field_name' => 'required|string|max:255|unique:health_report_fields,field_name,' . $field->id,
                'placeholder' => 'nullable|string|max:255',
                'options' => 'nullable|string',
                'is_required' => 'boolean',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            // Parse options if provided
            $options = $field->options;
            if ($request->filled('options') && $request->field_type === 'select') {
                $optionsArray = array_filter(
                    array_map('trim', explode("\n", $request->options)),
                    function($option) {
                        return !empty($option);
                    }
                );
                $options = $optionsArray;
            } elseif ($request->field_type !== 'select') {
                $options = null;
            }

            $field->update([
                'category_id' => $request->category_id,
                'label' => $request->label,
                'field_type' => $request->field_type,
                'field_name' => $request->field_name,
                'placeholder' => $request->placeholder,
                'options' => $options,
                'is_required' => $request->boolean('is_required'),
                'is_active' => $request->boolean('is_active'),
                'sort_order' => $request->sort_order ?? $field->sort_order,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Field updated successfully.',
                'field' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update field: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteField(HealthReportField $field)
    {
        try {
            // Check if field is being used in any reports
            $usageCount = StudentHealthReportData::where('field_id', $field->id)->count();
            
            if ($usageCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete field. It is being used in ' . $usageCount . ' health report(s).'
                ], 422);
            }

            $field->delete();

            return response()->json([
                'success' => true,
                'message' => 'Field deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete field: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(StudentHealthReport $healthReport)
    {
        $student = $healthReport->student;
        $user = $student->user;
        
        // Get all active categories with fields
        $categories = HealthReportCategory::with(['activeFields'])
            ->active()
            ->ordered()
            ->get();

        return view('backend.health-reports.edit', compact('healthReport', 'student', 'user', 'categories'));
    }

    public function update(Request $request, StudentHealthReport $healthReport)
    {
        try {
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

    private function ensureHealthCardExists(Student $student)
    {
        $healthCard = HealthCard::where('student_id', $student->id)->first();
        
        if (!$healthCard) {
            // Generate card number
            $latestCard = HealthCard::orderBy('id', 'desc')->first();
            $cardNumber = 'HCB-' . str_pad(($latestCard ? $latestCard->id + 1 : 1), 4, '0', STR_PAD_LEFT);
            
            // Create health card
            $healthCard = HealthCard::create([
                'student_id' => $student->id,
                'card_number' => $cardNumber,
                'issue_date' => now(),
                'expiry_date' => now()->addYear(),
                'status' => 'active',
                'qr_code' => 'QR' . str_pad($student->id, 4, '0', STR_PAD_LEFT),
                'medical_summary' => 'Auto-generated health card',
                'emergency_instructions' => 'Contact parent/guardian',
            ]);
        }
        
        return $healthCard;
    }

    /**
     * Ensure health report exists for student - create basic one if doesn't exist
     */
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
                'school_id' => $student->user->school_id,
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

    /**
     * Get default value for a field based on field type and student data
     */
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

    /**
     * Calculate age group based on student's date of birth
     */
    private function calculateAgeGroup(Student $student)
    {
        if (!$student->user->date_of_birth) {
            return '6-12'; // Default age group
        }
        
        $age = $student->user->date_of_birth->age;
        
        if ($age <= 1) return '0-1';
        if ($age <= 5) return '2-5';
        if ($age <= 12) return '6-12';
        return '13-18';
    }
}