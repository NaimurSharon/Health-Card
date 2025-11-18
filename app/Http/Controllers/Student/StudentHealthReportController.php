<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentHealthReportController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        // Health Reports - Fixed relationship
        $healthReports = \App\Models\StudentHealthReport::where('student_id', $studentDetails->id)
            ->with(['reportData.field.category']) // Fixed relationship name
            ->orderBy('checkup_date', 'desc')
            ->get();

        // Medical Records (excluding vaccinations)
        $medicalRecords = \App\Models\MedicalRecord::where('student_id', $studentDetails->id)
            ->where('record_type', '!=', 'vaccination')
            ->orderBy('record_date', 'desc')
            ->get();

        // Vaccination Records
        $vaccinationRecords = \App\Models\VaccinationRecord::where('student_id', $studentDetails->id)
            ->orderBy('vaccine_date', 'desc')
            ->get();

        // Also get vaccinations from medical records
        $medicalVaccinations = \App\Models\MedicalRecord::where('student_id', $studentDetails->id)
            ->where('record_type', 'vaccination')
            ->orderBy('record_date', 'desc')
            ->get();

        // Combine both vaccination sources
        $allVaccinations = $vaccinationRecords->merge($medicalVaccinations)->sortByDesc(function($item) {
            return $item->vaccine_date ?? $item->record_date;
        });

        // Active Health Card
        $activeHealthCard = \App\Models\HealthCard::where('student_id', $studentDetails->id)
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->first();

        // School and Class Information
        $school = $student->school;
        $class = $studentDetails->class;
        $section = $studentDetails->section;

        // Get health report categories and fields for form display
        $healthCategories = \App\Models\HealthReportCategory::with(['activeFields'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('student.health-report.index', compact(
            'healthReports', 
            'medicalRecords', 
            'allVaccinations',
            'activeHealthCard',
            'studentDetails',
            'school',
            'class',
            'section',
            'healthCategories'
        ));
    }

    public function show($id)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        // Get specific health report with correct relationship
        $healthReport = \App\Models\StudentHealthReport::where('id', $id)
            ->where('student_id', $studentDetails->id)
            ->with(['reportData.field.category']) // Fixed relationship name
            ->firstOrFail();

        // Get all categories with fields for display
        $categories = \App\Models\HealthReportCategory::with(['activeFields'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Calculate BMI if weight and height are available
        $bmiData = $this->calculateBMI($healthReport);

        return view('student.health-report.show', compact(
            'healthReport',
            'studentDetails',
            'categories',
            'bmiData'
        ));
    }

    public function uploadPrescription(Request $request)
    {
        $request->validate([
            'prescription_name' => 'required|string|max:255',
            'prescription_file' => 'required|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'prescription_date' => 'required|date'
        ]);

        // Handle file upload
        if ($request->hasFile('prescription_file')) {
            $file = $request->file('prescription_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('prescriptions', $fileName, 'public');

            // Save to medical records
            $student = Auth::user();
            $studentDetails = $student->student;

            \App\Models\MedicalRecord::create([
                'student_id' => $studentDetails->id,
                'record_date' => $request->prescription_date,
                'record_type' => 'routine',
                'doctor_notes' => "Prescription: " . $request->prescription_name,
                'prescription' => $filePath,
                'recorded_by' => $student->id
            ]);

            return redirect()->back()->with('success', 'Prescription uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload prescription.');
    }

    private function calculateBMI($healthReport)
    {
        $weight = null;
        $height = null;
        $bmi = null;
        $bmiCategory = '';
        $bmiColor = '';

        // Extract weight and height from health report data
        foreach ($healthReport->reportData as $data) {
            if ($data->field && $data->field->field_name === 'weight_kg') {
                $weight = floatval($data->field_value);
            }
            if ($data->field && $data->field->field_name === 'height_cm') {
                $height = floatval($data->field_value);
            }
        }

        if ($weight && $height && $height > 0) {
            $heightInMeters = $height / 100;
            $bmi = $weight / ($heightInMeters * $heightInMeters);
            
            if ($bmi < 18.5) {
                $bmiCategory = 'Underweight';
                $bmiColor = 'yellow';
            } elseif ($bmi >= 18.5 && $bmi < 25) {
                $bmiCategory = 'Normal weight';
                $bmiColor = 'green';
            } elseif ($bmi >= 25 && $bmi < 30) {
                $bmiCategory = 'Overweight';
                $bmiColor = 'orange';
            } else {
                $bmiCategory = 'Obese';
                $bmiColor = 'red';
            }
        }

        return [
            'weight' => $weight,
            'height' => $height,
            'bmi' => $bmi,
            'category' => $bmiCategory,
            'color' => $bmiColor
        ];
    }

    /**
     * Format field value for display based on field type
     */
    private function formatFieldValue($value, $fieldType)
    {
        if (!$value || $value === 'Not Recorded') {
            return $value;
        }
        
        switch($fieldType) {
            case 'date':
                try {
                    return \Carbon\Carbon::parse($value)->format('M j, Y');
                } catch (\Exception $e) {
                    return $value;
                }
            case 'checkbox':
                return $value === '1' ? 'Yes' : 'No';
            case 'number':
                return is_numeric($value) ? number_format($value, 2) : $value;
            case 'select':
                return ucfirst($value);
            default:
                return $value;
        }
    }
}