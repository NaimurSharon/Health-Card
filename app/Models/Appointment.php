<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // Changed from student_id
        'patient_type',     // New field
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'reason',
        'symptoms',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i:s',
    ];
    
    // Relationship with the user (patient)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Keep student relationship for backward compatibility
    // Links via users table since students.user_id = appointments.user_id
    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeNoShow($query)
    {
        return $query->where('status', 'no_show');
    }

    public function scopeToday($query)
    {
        return $query->where('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
                    ->where('status', 'scheduled');
    }

    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', today())
                    ->orWhere(function($q) {
                        $q->where('appointment_date', today())
                          ->where('appointment_time', '<', now()->format('H:i:s'));
                    });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    // New scopes for patient type
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

    public function scopeByPatientType($query, $type)
    {
        return $query->where('patient_type', $type);
    }

    // Helper methods
    public function getAppointmentDateTimeAttribute()
    {
        return $this->appointment_date->format('Y-m-d') . ' ' . $this->appointment_time;
    }

    public function getFormattedAppointmentTimeAttribute()
    {
        return \Carbon\Carbon::createFromFormat('H:i:s', $this->appointment_time)->format('h:i A');
    }

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

    public function getDoctorNameAttribute()
    {
        return $this->doctor ? $this->doctor->name : 'Unknown Doctor';
    }

    // Status checks
    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isNoShow()
    {
        return $this->status === 'no_show';
    }

    public function isUpcoming()
    {
        return $this->isScheduled() && 
               ($this->appointment_date > today() || 
               ($this->appointment_date == today() && 
                $this->appointment_time > now()->format('H:i:s')));
    }

    public function canBeCancelled()
    {
        if (!$this->isScheduled()) {
            return false;
        }

        $appointmentDateTime = \Carbon\Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);
        return $appointmentDateTime->diffInHours(now()) > 2;
    }

    public function canBeRescheduled()
    {
        return $this->isScheduled() && $this->canBeCancelled();
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
                'school_id' => $this->student->school_id,
            ];
        }

        return [
            'type' => $this->patient_type,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->patient_name,
                'email' => $this->patient_email,
                'phone' => $this->patient_phone,
                'role' => $this->user ? $this->user->role : null,
            ],
        ];
    }

    // Check if user is the patient
    public function isPatient($userId)
    {
        return $this->user_id == $userId;
    }

    // Check if user is the doctor
    public function isDoctor($userId)
    {
        return $this->doctor_id == $userId;
    }

    // Check if user can access this appointment
    public function canAccess($userId)
    {
        return $this->isPatient($userId) || 
               $this->isDoctor($userId) || 
               $this->created_by == $userId;
    }
}