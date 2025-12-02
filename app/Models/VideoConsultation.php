<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoConsultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_id',
        'user_id',          // Changed from student_id to user_id
        'patient_type',     // Added: student, teacher, doctor, public, other
        'doctor_id',
        'appointment_id',
        'type',
        'symptoms',
        'status',
        'scheduled_for',
        'started_at',
        'ended_at',
        'duration',
        'prescription',
        'doctor_notes',
        'consultation_fee',
        'payment_status',
        'call_metadata'
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'call_metadata' => 'array',
        'is_available' => 'boolean',
    ];

    // Relationship with the user (patient)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Keep student relationship for backward compatibility
    // This assumes students.user_id = video_consultations.user_id
    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

    // Relationship with doctor (who is also a user)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payment()
    {
        return $this->hasOne(VideoCallPayment::class, 'consultation_id');
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Updated scope names for clarity instead of using student_id
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    // New scope to filter by patient type
    public function scopeByPatientType($query, $type)
    {
        return $query->where('patient_type', $type);
    }

    // New scope for specific user types
    public function scopeForStudents($query)
    {
        return $query->where('patient_type', 'student');
    }

    public function scopeForTeachers($query)
    {
        return $query->where('patient_type', 'teacher');
    }

    public function scopeForPublic($query)
    {
        return $query->where('patient_type', 'public');
    }

    public function canStartCall()
    {
        // Can start if scheduled and within time window (15 min before to 2 hours after)
        if ($this->status === 'scheduled') {
            $now = now();
            $scheduledTime = $this->scheduled_for;
            $startWindow = $scheduledTime->copy()->subMinutes(15); // 15 min before
            $endWindow = $scheduledTime->copy()->addHours(2);      // 2 hours after
            
            return $now->between($startWindow, $endWindow);
        }
        
        // Can also join if already ongoing
        return $this->status === 'ongoing';
    }

    public function isActive()
    {
        return $this->status === 'ongoing';
    }
    
    /**
     * Check if consultation is available to join (scheduled time has arrived)
     */
    public function isAvailable()
    {
        return $this->canStartCall();
    }
    
    /**
     * Get status display text
     */
    public function getStatusDisplayAttribute()
    {
        if ($this->status === 'completed') {
            return 'Completed';
        }
        
        if ($this->status === 'cancelled') {
            return 'Cancelled';
        }
        
        if ($this->status === 'ongoing') {
            return 'Ongoing';
        }
        
        // For scheduled consultations
        if ($this->canStartCall()) {
            return 'Ready to Join';
        }
        
        // Not yet time
        $now = now();
        if ($this->scheduled_for->isFuture()) {
            $diff = $now->diffInMinutes($this->scheduled_for);
            if ($diff < 60) {
                return "Starts in {$diff} minutes";
            } elseif ($diff < 1440) { // Less than 24 hours
                $hours = floor($diff / 60);
                return "Starts in {$hours} hour" . ($hours > 1 ? 's' : '');
            } else {
                return 'Scheduled';
            }
        }
        
        return 'Not Started';
    }

    public function calculateDuration()
    {
        if ($this->started_at && $this->ended_at) {
            return $this->started_at->diffInSeconds($this->ended_at);
        }
        return null;
    }

    // Helper methods to get patient information
    public function getPatientNameAttribute()
    {
        return $this->user ? $this->user->name : 'Unknown Patient';
    }

    public function getPatientEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    public function getPatientPhoneAttribute()
    {
        return $this->user ? $this->user->phone : null;
    }

    public function getPatientRoleAttribute()
    {
        return $this->user ? $this->user->role : null;
    }

    public function getDoctorNameAttribute()
    {
        return $this->doctor ? $this->doctor->name : 'Unknown Doctor';
    }

    // Check if the user is the patient in this consultation
    public function isPatient($userId)
    {
        return $this->user_id == $userId;
    }

    // Check if the user is the doctor in this consultation
    public function isDoctor($userId)
    {
        return $this->doctor_id == $userId;
    }

    // Check if user can access this consultation
    public function canAccess($userId)
    {
        return $this->isPatient($userId) || $this->isDoctor($userId);
    }

    // Get patient details based on type
    public function getPatientDetails()
    {
        if ($this->patient_type === 'student' && $this->student) {
            return [
                'type' => 'student',
                'student_id' => $this->student->student_id,
                'class_id' => $this->student->class_id,
                'section_id' => $this->student->section_id,
                'roll_number' => $this->student->roll_number,
                'parent_name' => $this->student->father_name,
            ];
        }

        return [
            'type' => $this->patient_type,
            'user' => $this->user,
        ];
    }
}