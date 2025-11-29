<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'requested_by',
        'symptoms',
        'urgency',
        'priority',
        'status',
        'assigned_doctor',
        'notes'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function assignedDoctor()
    {
        return $this->belongsTo(User::class, 'assigned_doctor');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'high')->orWhere('urgency', 'urgent');
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('assigned_doctor', $doctorId);
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'assigned_doctor');
    }
}