<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipRegistration extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'registration_number',
        'academic_background',
        'extracurricular_activities',
        'achievements',
        'reason_for_applying',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(ScholarshipExam::class, 'exam_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
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

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Methods
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function generateRegistrationNumber()
    {
        return 'REG-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            $registration->registration_number = 'REG-' . date('Y') . '-' . str_pad(ScholarshipRegistration::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}