<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'class_id', 'section_id', 'roll_number',
        'parent_id', 'blood_group', 'allergies', 'medical_conditions',
        'emergency_contact', 'admission_date'
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function healthCard()
    {
        return $this->hasOne(HealthCard::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class);
    }

    public function diaryUpdates()
    {
        return $this->hasMany(DiaryUpdate::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function scholarshipApplications()
    {
        return $this->hasMany(ScholarshipApplication::class);
    }

    public function treatmentRequests()
    {
        return $this->hasMany(TreatmentRequest::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}