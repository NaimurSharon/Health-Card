<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DoctorAvailability;
use App\Models\DoctorLeaveDate;
use App\Models\User;

class DoctorAvailabilityController extends Controller
{
    /**
     * Show availability management page
     */
    public function index()
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }
    
        // Load doctor details with availabilities and leave dates
        $doctor->load(['doctorDetail', 'doctorAvailabilities', 'doctorLeaveDates']);
        
        // Get all days of week
        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
    
        // Get existing availabilities
        $availabilities = [];
        foreach ($daysOfWeek as $day => $dayName) {
            $availability = $doctor->doctorAvailabilities->where('day_of_week', $day)->first();
            if ($availability) {
                // Format times properly for HTML input
                $startTime = \Carbon\Carbon::parse($availability->start_time)->format('H:i');
                $endTime = \Carbon\Carbon::parse($availability->end_time)->format('H:i');
                
                $availabilities[$day] = [
                    'enabled' => true,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'slot_duration' => $availability->slot_duration,
                    'max_appointments' => $availability->max_appointments,
                ];
            } else {
                $availabilities[$day] = [
                    'enabled' => false,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'slot_duration' => 30,
                    'max_appointments' => 10,
                ];
            }
        }
    
        // Get upcoming leave dates
        $leaveDates = $doctor->doctorLeaveDates()
            ->where('leave_date', '>=', now()->format('Y-m-d'))
            ->orderBy('leave_date', 'asc')
            ->get();
    
        // Get past leave dates for reference
        $pastLeaveDates = $doctor->doctorLeaveDates()
            ->where('leave_date', '<', now()->format('Y-m-d'))
            ->orderBy('leave_date', 'desc')
            ->take(10)
            ->get();
    
        return view('doctor.availability.index', compact(
            'doctor', 
            'daysOfWeek', 
            'availabilities', 
            'leaveDates',
            'pastLeaveDates'
        ));
    }

    public function updateAvailability(Request $request)
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }
    
        // Validate the request
        $validator = validator($request->all(), [
            'availabilities' => 'required|array',
            'availabilities.*.enabled' => 'sometimes|boolean',
            'availabilities.*.start_time' => 'nullable|date_format:H:i',
            'availabilities.*.end_time' => 'nullable|date_format:H:i',
            'availabilities.*.slot_duration' => 'nullable|integer|min:15|max:120',
            'availabilities.*.max_appointments' => 'nullable|integer|min:1|max:50',
        ]);
    
        // Custom validation for enabled days
        $hasValidAvailability = false;
        $validationErrors = [];
    
        if ($request->has('availabilities')) {
            foreach ($request->availabilities as $day => $availability) {
                // Only validate if the day is enabled
                if (isset($availability['enabled']) && $availability['enabled'] == '1') {
                    if (empty($availability['start_time']) || empty($availability['end_time'])) {
                        $validationErrors["availabilities.{$day}.start_time"] = "Start time and end time are required for " . ucfirst($day) . ".";
                        continue;
                    }
    
                    // Validate time format and logic
                    try {
                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $availability['start_time']);
                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $availability['end_time']);
                        
                        if ($endTime <= $startTime) {
                            $validationErrors["availabilities.{$day}.end_time"] = "End time must be after start time for " . ucfirst($day) . ".";
                            continue;
                        }
                        
                        $hasValidAvailability = true;
                    } catch (\Exception $e) {
                        $validationErrors["availabilities.{$day}.start_time"] = "Invalid time format for " . ucfirst($day) . ".";
                    }
                }
            }
        }
    
        // Add custom validation errors
        foreach ($validationErrors as $field => $message) {
            $validator->errors()->add($field, $message);
        }
    
        if (!$hasValidAvailability) {
            $validator->errors()->add('availabilities', 'At least one day must be available with valid time slots.');
        }
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        try {
            DB::transaction(function () use ($request, $doctor) {
                // Delete all existing availabilities
                $doctor->doctorAvailabilities()->delete();
                
                // Create new availabilities for enabled days
                foreach ($request->availabilities as $day => $availability) {
                    if (isset($availability['enabled']) && $availability['enabled'] == '1' && 
                        !empty($availability['start_time']) && !empty($availability['end_time'])) {
                        
                        // Ensure time format is correct
                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $availability['start_time'])->format('H:i:s');
                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $availability['end_time'])->format('H:i:s');
                        
                        DoctorAvailability::create([
                            'doctor_id' => $doctor->id,
                            'day_of_week' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'slot_duration' => $availability['slot_duration'] ?? 30,
                            'max_appointments' => $availability['max_appointments'] ?? 10,
                            'is_available' => true,
                        ]);
                    }
                }
    
                // Update doctor's overall availability status
                if ($doctor->doctorDetail) {
                    $doctor->doctorDetail->update([
                        'is_available' => $hasValidAvailability
                    ]);
                }
            });
    
            return redirect()->route('doctor.availability.index')
                ->with('success', 'Availability schedule updated successfully.');
    
        } catch (\Exception $e) {
            
            return redirect()->back()
                ->with('error', 'Failed to update availability: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store a new leave date
     */
    public function storeLeaveDate(Request $request)
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'leave_date' => 'required|date|after:yesterday',
            'reason' => 'required|string|max:255',
            'is_full_day' => 'required|boolean',
            'start_time' => 'required_if:is_full_day,0|date_format:H:i|nullable',
            'end_time' => 'required_if:is_full_day,0|date_format:H:i|after:start_time|nullable',
        ]);

        // Check if leave date already exists
        $existingLeave = DoctorLeaveDate::where('doctor_id', $doctor->id)
            ->where('leave_date', $request->leave_date)
            ->first();

        if ($existingLeave) {
            return redirect()->back()
                ->with('error', 'Leave date already exists for the selected date.')
                ->withInput();
        }

        DoctorLeaveDate::create([
            'doctor_id' => $doctor->id,
            'leave_date' => $request->leave_date,
            'reason' => $request->reason,
            'is_full_day' => $request->is_full_day,
            'start_time' => $request->is_full_day ? null : $request->start_time,
            'end_time' => $request->is_full_day ? null : $request->end_time,
        ]);

        return redirect()->route('doctor.availability.index')
            ->with('success', 'Leave date added successfully.');
    }

    /**
     * Delete a leave date
     */
    public function destroyLeaveDate(DoctorLeaveDate $leaveDate)
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor' || $leaveDate->doctor_id != $doctor->id) {
            abort(403, 'Unauthorized access.');
        }

        $leaveDate->delete();

        return redirect()->route('doctor.availability.index')
            ->with('success', 'Leave date deleted successfully.');
    }

    /**
     * Toggle overall availability status
     */
    public function toggleAvailability()
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            abort(403, 'Unauthorized access.');
        }

        if ($doctor->doctorDetail) {
            $newStatus = !$doctor->doctorDetail->is_available;
            $doctor->doctorDetail->update([
                'is_available' => $newStatus
            ]);

            $statusText = $newStatus ? 'available' : 'unavailable';
            return redirect()->route('doctor.availability.index')
                ->with('success', "You are now {$statusText} for appointments.");
        }

        return redirect()->route('doctor.availability.index')
            ->with('error', 'Doctor details not found.');
    }

    /**
     * Get time slots for a specific day
     */
    public function getTimeSlots($day)
    {
        $doctor = Auth::user();
        
        if ($doctor->role !== 'doctor') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $availability = $doctor->doctorAvailabilities()
            ->where('day_of_week', $day)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return response()->json(['slots' => []]);
        }

        $slots = $availability->getTimeSlots();

        return response()->json(['slots' => $slots]);
    }
}