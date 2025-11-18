<?php

namespace App\Http\Controllers;

use App\Models\IdCard;
use App\Models\IdCardTemplate;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IdCardController extends Controller
{
    public function index(Request $request)
    {
        $query = IdCard::with(['student.user', 'user', 'template']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $idCards = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('backend.id-cards.index', compact('idCards'));
    }

    public function create()
    {
        $students = Student::with('user')->active()->get();
        $staff = User::whereIn('role', ['teacher', 'medical_staff'])->where('status', 'active')->get();
        $templates = IdCardTemplate::active()->get();
        
        return view('backend.id-cards.form', compact('students', 'staff', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:student,teacher,staff,medical',
            'card_number' => 'required|string|unique:id_cards',
            'template_id' => 'required|exists:id_card_templates,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'required|in:active,expired,lost',
        ]);

        if ($request->student_id && $request->user_id) {
            return back()->with('error', 'Please select either a student or staff member, not both.');
        }

        if (!$request->student_id && !$request->user_id) {
            return back()->with('error', 'Please select either a student or staff member.');
        }

        $idCardData = $request->all();
        
        // Generate QR Code with profile link
        $qrCodePath = $this->generateQRCode($request->card_number, $request->student_id, $request->user_id);
        $idCardData['qr_code'] = $qrCodePath;

        // Generate Barcode
        $barcodePath = $this->generateBarcode($request->card_number);
        $idCardData['barcode'] = $barcodePath;

        IdCard::create($idCardData);

        return redirect()->route('admin.id-cards.index')
            ->with('success', 'ID Card created successfully.');
    }

    public function show(IdCard $idCard)
    {
        $idCard->load(['student.user', 'user', 'template']);
        return view('backend.id-cards.show', compact('idCard'));
    }

    public function edit(IdCard $idCard)
    {
        $students = Student::with('user')->active()->get();
        $staff = User::whereIn('role', ['teacher', 'medical_staff'])->where('status', 'active')->get();
        $templates = IdCardTemplate::active()->get();
        
        return view('backend.id-cards.form', compact('idCard', 'students', 'staff', 'templates'));
    }

    public function update(Request $request, IdCard $idCard)
    {
        $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:student,teacher,staff,medical',
            'card_number' => 'required|string|unique:id_cards,card_number,' . $idCard->id,
            'template_id' => 'required|exists:id_card_templates,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'required|in:active,expired,lost',
        ]);

        if ($request->student_id && $request->user_id) {
            return back()->with('error', 'Please select either a student or staff member, not both.');
        }

        if (!$request->student_id && !$request->user_id) {
            return back()->with('error', 'Please select either a student or staff member.');
        }

        $idCard->update($request->all());

        return redirect()->route('admin.id-cards.index')
            ->with('success', 'ID Card updated successfully.');
    }

    public function destroy(IdCard $idCard)
    {
        // Delete associated QR code and barcode
        if ($idCard->qr_code) {
            $this->deleteImage($idCard->qr_code);
        }
        if ($idCard->barcode) {
            $this->deleteImage($idCard->barcode);
        }

        $idCard->delete();

        return redirect()->route('admin.id-cards.index')
            ->with('success', 'ID Card deleted successfully.');
    }

    public function print(IdCard $idCard)
    {
        $idCard->load(['student.user', 'user', 'template']);
        return view('backend.id-cards.print', compact('idCard'));
    }

    public function bulkPrint(Request $request)
    {
        $idCards = IdCard::with(['student.user', 'user', 'template'])
            ->whereIn('id', $request->ids)
            ->get();

        return view('backend.id-cards.bulk-print', compact('idCards'));
    }

    /**
     * Generate QR Code for ID Card
     */
    private function generateQRCode($cardNumber, $studentId = null, $userId = null)
    {
        // Detect current full domain (with subdomain)
        $currentDomain = request()->getHost(); // e.g. healthcard.facreativefirm.com
    
        // Ensure HTTPS
        $scheme = request()->isSecure() ? 'https://' : 'http://';
        
        // Build the profile URL dynamically using the detected subdomain/domain
        $baseUrl = $scheme . $currentDomain;
    
        // Generate profile URL based on user type
        $profileUrl = $this->getProfileUrl($studentId, $userId, $cardNumber, $baseUrl);
    
        // Generate the QR Code
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate($profileUrl);
    
        $fileName = 'qrcodes/' . Str::uuid() . '.png';
        Storage::disk('public')->put($fileName, $qrCode);
    
        return $fileName;
    }


    /**
     * Generate Barcode for ID Card
     */
    private function generateBarcode($cardNumber)
    {
        // Using simple barcode generation (you can use a barcode library like milon/barcode)
        $barcode = QrCode::format('png') // Using QR as placeholder, replace with actual barcode
            ->size(300)
            ->generate($cardNumber);

        $fileName = 'barcodes/' . Str::uuid() . '.png';
        Storage::disk('public')->put($fileName, $barcode);

        return $fileName;
    }
    
    private function getProfileUrl($studentId, $userId, $cardNumber)
    {
        $baseUrl = config('app.url');
        
        if ($studentId) {
            // Student profile URL
            return $baseUrl . '/students/' . $studentId . '/profile?card=' . $cardNumber;
        } elseif ($userId) {
            // Staff/Teacher profile URL
            return $baseUrl . '/users/' . $userId . '/profile?card=' . $cardNumber;
        } else {
            // Fallback to card number lookup
            return $baseUrl . '/id-cards/verify/' . $cardNumber;
        }
    }

    /**
     * Delete image from storage
     */
    protected function deleteImage($imagePath)
    {
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($imagePath);
            $imagePath = ltrim($parsedUrl['path'], '/');
            
            if (strpos($imagePath, 'public/') === 0) {
                $imagePath = substr($imagePath, 7);
            }
        }
        
        $fullPath = public_path('storage/' . $imagePath);
        if ($imagePath && file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}