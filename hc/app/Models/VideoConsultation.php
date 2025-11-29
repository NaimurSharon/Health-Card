<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoConsultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_id',
        'student_id',
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
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

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

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function canStartCall()
    {
        return in_array($this->status, ['scheduled']) && 
               $this->scheduled_for <= now()->addMinutes(10);
    }

    public function isActive()
    {
        return $this->status === 'ongoing';
    }

    public function calculateDuration()
    {
        if ($this->started_at && $this->ended_at) {
            return $this->started_at->diffInSeconds($this->ended_at);
        }
        return null;
    }
}