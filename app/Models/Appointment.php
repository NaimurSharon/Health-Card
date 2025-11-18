<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
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
    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
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

    public function scopeToday($query)
    {
        return $query->where('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
                    ->where('status', 'scheduled');
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}