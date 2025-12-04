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
            ->with(['reportData.field.category'])
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
        $allVaccinations = $vaccinationRecords->merge($medicalVaccinations)->sortByDesc(function ($item) {
            return $item->vaccine_date ?? $item->record_date;
        });

        // Active Health Card
        $activeHealthCard = \App\Models\HealthCard::where('user_id', $student->id)
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

    public function show()
    {
        $student = Auth::user();
        // Ensure studentDetails relationship is loaded if not already
        $studentDetails = $student->student;


        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }


        // Get specific health report with correct relationship
        // Note: The second 'where' clause is redundant but kept for consistency
        $healthReport = \App\Models\StudentHealthReport::where('student_id', $studentDetails->id)
            ->where('student_id', $studentDetails->id)
            ->with(['reportData.field.category'])
            // Changed from firstOrFail() to first() to allow the Blade to handle missing report gracefully
            ->first();


        // Get all categories with fields for display
        $categories = \App\Models\HealthReportCategory::with(['fields'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // School and Class Information
        $school = $student->school;

        // Ensure class and section relationships are loaded
        $studentDetails->load(['class', 'section']);
        $class = $studentDetails->class;
        $section = $studentDetails->section;

        $annualRecords = \App\Models\AnnualHealthRecord::where('student_id', $studentDetails->id)
            ->latestFirst()
            ->get();

        // 💡 NEW ADDITION: Fetch Active Health Card
        $activeHealthCard = \App\Models\HealthCard::where('user_id', $student->id)
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->first();


        // Pre-calculate field values and formatted values for the view
        $fieldValues = [];
        if ($healthReport) {
            foreach ($categories as $category) {
                foreach ($category->fields as $field) {
                    $value = $this->getFieldValueForReport($healthReport, $field->field_name);
                    $formattedValue = $this->formatFieldValue($value, $field->field_type);
                    $fieldValues[$field->id] = [
                        'value' => $value,
                        'formatted' => $formattedValue
                    ];
                }
            }
        }


        return view('student.health-report.show', compact(
            'healthReport',
            'studentDetails',
            'categories',
            'annualRecords',
            'school',
            'student',
            'class',
            'section',
            'fieldValues',
            // 💡 NEW ADDITION: Pass to the view
            'activeHealthCard'
        ));
    }

    /**
     * Get field value for a specific health report
     */
    private function getFieldValueForReport($healthReport, $fieldName)
    {
        foreach ($healthReport->reportData as $data) {
            if ($data->field && $data->field->field_name === $fieldName) {
                return $data->field_value;
            }
        }
        return null;
    }

    /**
     * Format field value for display based on field type
     */
    private function formatFieldValue($value, $fieldType)
    {
        if (!$value || $value === 'Not Recorded' || $value === 'null') {
            return null;
        }

        switch ($fieldType) {
            case 'date':
                try {
                    return \Carbon\Carbon::parse($value)->format('M j, Y');
                } catch (\Exception $e) {
                    return $value;
                }
            case 'checkbox':
                return $value === '1' || $value === 1 || $value === true ? 'Yes' : 'No';
            case 'number':
                return is_numeric($value) ? number_format($value, 2) : $value;
            case 'select':
                return ucfirst($value);
            default:
                return $value;
        }
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

    /**
     * Print health report
     */
    public function print()
    {
        $student = Auth::user();
        $studentDetails = $student->student;

        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        $healthReport = \App\Models\StudentHealthReport::where('student_id', $studentDetails->id)
            ->with(['reportData.field.category'])
            ->first();

        $categories = \App\Models\HealthReportCategory::with(['fields'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $school = $student->school;
        $studentDetails->load(['class', 'section']);
        $class = $studentDetails->class;
        $section = $studentDetails->section;

        $annualRecords = \App\Models\AnnualHealthRecord::where('student_id', $studentDetails->id)
            ->latestFirst()
            ->get();

        $activeHealthCard = \App\Models\HealthCard::where('user_id', $student->id)
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->first();

        $fieldValues = [];
        if ($healthReport) {
            foreach ($categories as $category) {
                foreach ($category->fields as $field) {
                    $value = $this->getFieldValueForReport($healthReport, $field->field_name);
                    $formattedValue = $this->formatFieldValue($value, $field->field_type);
                    $fieldValues[$field->id] = [
                        'value' => $value,
                        'formatted' => $formattedValue
                    ];
                }
            }
        }

        return view('student.health-report.print', compact(
            'healthReport',
            'studentDetails',
            'categories',
            'annualRecords',
            'school',
            'student',
            'class',
            'section',
            'fieldValues',
            'activeHealthCard'
        ));
    }

    /**
     * Download health report as PDF
     */
    public function downloadPdf()
    {
        $student = Auth::user();
        $studentDetails = $student->student;

        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        $healthReport = \App\Models\StudentHealthReport::where('student_id', $studentDetails->id)
            ->with(['reportData.field.category'])
            ->first();

        $categories = \App\Models\HealthReportCategory::with(['fields'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $school = $student->school;
        $studentDetails->load(['class', 'section']);
        $class = $studentDetails->class;
        $section = $studentDetails->section;

        $annualRecords = \App\Models\AnnualHealthRecord::where('student_id', $studentDetails->id)
            ->latestFirst()
            ->get();

        $activeHealthCard = \App\Models\HealthCard::where('user_id', $student->id)
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->first();

        $fieldValues = [];
        if ($healthReport) {
            foreach ($categories as $category) {
                foreach ($category->fields as $field) {
                    $value = $this->getFieldValueForReport($healthReport, $field->field_name);
                    $formattedValue = $this->formatFieldValue($value, $field->field_type);
                    $fieldValues[$field->id] = [
                        'value' => $value,
                        'formatted' => $formattedValue
                    ];
                }
            }
        }

        // Render the view to HTML
        $html = view('student.health-report.pdf', compact(
            'healthReport',
            'studentDetails',
            'categories',
            'annualRecords',
            'school',
            'student',
            'class',
            'section',
            'fieldValues',
            'activeHealthCard'
        ))->render();

        // Create mPDF instance with Bengali font support
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // Ensure temp directory exists
        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'tempDir' => $tempDir,
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'notoSansBengali' => [
                    'R' => 'NotoSansBengali.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
                'nikosh' => [
                    'R' => 'Nikosh.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ]
            ],
            'default_font' => 'notoSansBengali',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        $fileName = 'health-report-' . $studentDetails->id . '-' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * View prescription details
     */
    public function viewPrescription($id)
    {
        $student = Auth::user();
        $studentDetails = $student->student;

        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        // Get the prescription record
        $prescription = \App\Models\MedicalRecord::where('id', $id)
            ->where('user_id', $student->id)
            ->whereNotNull('prescription')
            ->with(['recordedBy', 'student'])
            ->firstOrFail();

        return view('student.prescription.view', compact('prescription', 'studentDetails'));
    }

    /**
     * Download prescription as PDF
     */
    public function downloadPrescriptionPdf($id)
    {
        $student = Auth::user();
        $studentDetails = $student->student;

        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        // Get the prescription record
        $prescription = \App\Models\MedicalRecord::where('id', $id)
            ->where('user_id', $student->id)
            ->whereNotNull('prescription')
            ->with(['recordedBy', 'student'])
            ->firstOrFail();

        // Render the view to HTML
        $html = view('student.prescription.pdf', compact('prescription', 'studentDetails'))->render();

        // Create mPDF instance with Bengali font support
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // Ensure temp directory exists
        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'tempDir' => $tempDir,
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'notoSansBengali' => [
                    'R' => 'NotoSansBengali.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'notoSansBengali',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        $fileName = 'prescription-' . $prescription->id . '-' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

}