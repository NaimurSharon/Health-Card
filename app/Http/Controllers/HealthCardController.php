<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HealthCard;
use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HealthCardController extends Controller
{
    public function index()
    {
        $healthCards = HealthCard::with('user')
            ->orderBy('expiry_date', 'desc')
            ->paginate(20);

        return view('backend.medical.health-cards.index', compact('healthCards'));
    }

    public function create()
    {
        // Get all users who don't have a health card yet
        $users = User::whereDoesntHave('healthCard')
            ->whereIn('role', ['student', 'teacher', 'staff'])
            ->get();

        return view('backend.medical.health-cards.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:health_cards,user_id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'medical_summary' => 'nullable|string',
            'emergency_instructions' => 'nullable|string',
        ]);

        $healthCard = HealthCard::createForUser($request->user_id, [
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'medical_summary' => $request->medical_summary,
            'emergency_instructions' => $request->emergency_instructions,
            'status' => 'active',
        ]);

        // Generate QR code
        if ($healthCard) {
            $qrCodePath = $this->generateAndSaveQrCode($healthCard->card_number);
            $healthCard->update(['qr_code' => $qrCodePath]);
        }

        return redirect()->route('admin.health-cards.index')
            ->with('success', 'Health card created successfully.');
    }

    public function show(HealthCard $healthCard)
    {
        $healthCard->load('user');
        
        // Get medical records for this user
        $medicalRecords = \App\Models\MedicalRecord::where('user_id', $healthCard->user_id)
            ->orderBy('record_date', 'desc')
            ->take(10)
            ->get();
            
        return view('backend.medical.health-cards.show', compact('healthCard', 'medicalRecords'));
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

        $healthCards = HealthCard::with(['user'])
            ->whereHas('user.medicalRecords', function($query) use ($doctorId) {
                $query->where('recorded_by', $doctorId);
            });

        if ($search) {
            $healthCards->where(function($query) use ($search) {
                $query->where('card_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function($query) use ($search) {
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
        $healthCard->load(['user']);
        
        // Get medical records for this user recorded by this doctor
        $medicalRecords = \App\Models\MedicalRecord::where('user_id', $healthCard->user_id)
            ->where('recorded_by', auth()->id())
            ->orderBy('record_date', 'desc')
            ->get();

        return view('doctor.health-cards.show', compact('healthCard', 'medicalRecords'));
    }

    public function destroy(HealthCard $healthCard)
    {
        $healthCard->delete();

        return redirect()->route('admin.health-cards.index')
            ->with('success', 'Health card deleted successfully.');
    }

    public function print(HealthCard $healthCard)
    {
        $healthCard->load('user');
        
        $medicalRecords = \App\Models\MedicalRecord::where('user_id', $healthCard->user_id)
            ->orderBy('record_date', 'desc')
            ->take(10)
            ->get();
            
        return view('backend.medical.health-cards.print', compact('healthCard', 'medicalRecords'));
    }

    private function generateAndSaveQrCode($cardNumber)
    {
        $qrCodeData = route('student.id-cards.verify', ['cardNumber' => $cardNumber]);
        
        // Generate QR code image
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($qrCodeData);
        
        // Save to storage
        $fileName = 'qr-codes/health-card-' . $cardNumber . '.png';
        $path = storage_path('app/public/' . $fileName);
        
        // Ensure directory exists
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($path, $qrCode);
        
        return $fileName;
    }
}