<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'record_date',
        'record_type',
        'symptoms',
        'diagnosis',
        'prescription',
        'medication',
        'doctor_notes',
        'height',
        'weight',
        'temperature',
        'blood_pressure',
        'follow_up_date',
        'recorded_by'
    ];

    protected $casts = [
        'record_date' => 'date',
        'follow_up_date' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'temperature' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeEmergency($query)
    {
        return $query->where('record_type', 'emergency');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('record_date', '>=', now()->subDays($days));
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeRecordedByDoctor($query, $doctorId)
    {
        return $query->where('recorded_by', $doctorId);
    }
    
}