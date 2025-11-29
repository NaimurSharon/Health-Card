<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HealthCard;
use App\Models\Student;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HealthCardController extends Controller
{
    public function index()
    {
        $healthCards = HealthCard::with('student.user')
            ->orderBy('expiry_date', 'desc')
            ->paginate(20);

        return view('backend.medical.health-cards.index', compact('healthCards'));
    }

    public function create()
    {
        $students = Student::with('user')
            ->active()
            ->whereDoesntHave('healthCard')
            ->get();

        return view('backend.medical.health-cards.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id|unique:health_cards,student_id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'medical_summary' => 'nullable|string',
            'emergency_instructions' => 'nullable|string',
        ]);

        $cardNumber = 'HC' . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $healthCard = HealthCard::create([
            'student_id' => $request->student_id,
            'card_number' => $cardNumber,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'medical_summary' => $request->medical_summary,
            'emergency_instructions' => $request->emergency_instructions,
            'qr_code' => $this->generateQrCode($cardNumber),
        ]);

        return redirect()->route('admin.health-cards.index')
            ->with('success', 'Health card created successfully.');
    }

    public function show(HealthCard $healthCard)
    {
        $healthCard->load('student.user', 'student.medicalAlerts', 'student.medicalRecords');
        return view('backend.medical.health-cards.show', compact('healthCard'));
    }

    public function edit(HealthCard $healthCard)
    {
        return view('backend.medical.health-cards.edit', compact('healthCard'));
    }

    public function update(Request $request, HealthCard $healthCard)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'required|string',
            'medical_summary' => 'nullable|string',
            'emergency_instructions' => 'nullable|string',
        ]);

        $healthCard->update($request->all());

        return redirect()->route('admin.health-cards.index')
            ->with('success', 'Health card updated successfully.');
    }
    
        public function doctorIndex(Request $request)
    {
        $doctorId = auth()->id();
        $search = $request->get('search');

        $healthCards = HealthCard::with(['student.user'])
            ->whereHas('student.medicalRecords', function($query) use ($doctorId) {
                $query->recordedByDoctor($doctorId);
            });

        if ($search) {
            $healthCards->where(function($query) use ($search) {
                $query->where('card_number', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $healthCards = $healthCards->orderBy('expiry_date', 'desc')
            ->paginate(15);

        return view('doctor.health-cards.index', compact('healthCards', 'search'));
    }

    public function doctorView(HealthCard $healthCard)
    {
        $healthCard->load(['student.user', 'student.medicalRecords' => function($query) {
            $query->recordedByDoctor(auth()->id());
        }]);

        return view('doctor.health-cards.show', compact('healthCard'));
    }

    public function destroy(HealthCard $healthCard)
    {
        $healthCard->delete();

        return redirect()->route('admin.health-cards.index')
            ->with('success', 'Health card deleted successfully.');
    }

    public function print(HealthCard $healthCard)
    {
        $healthCard->load('student.user', 'student.medicalAlerts');
        return view('backend.medical.health-cards.print', compact('healthCard'));
    }

    private function generateQrCode($cardNumber)
    {
        $data = [
            'card_number' => $cardNumber,
            'timestamp' => now()->timestamp
        ];

        return QrCode::size(200)->generate(json_encode($data));
    }
}